<?php

namespace Tests\Feature\Cabinet;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\CategoriesIncome;
use App\Models\Transaction;

class ProfileImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_skips_duplicates()
    {
        $user = User::factory()->create();
        $sql = "INSERT INTO categories_income (name, created_at, updated_at) VALUES ('Зарплата', '2025-07-18 10:24:09', '2025-07-18 10:24:09');";

        CategoriesIncome::factory()->create(['user_id' => $user->id, 'name' => 'Зарплата']);

        Storage::fake('local');
        $file = UploadedFile::fake()->createWithContent('import.sql', $sql);

        $this->actingAs($user)
            ->post(route('cabinet.profile.import.sql'), ['sql_file' => $file])
            ->assertRedirect();

        $this->assertDatabaseCount('categories_income', 1);
    }

    public function test_import_saves_data_correctly()
    {
        $user = User::factory()->create();
        $sql = "INSERT INTO categories_income (name, created_at, updated_at) VALUES ('Фриланс', '2025-07-18 10:24:09', '2025-07-18 10:24:09');";

        $file = UploadedFile::fake()->createWithContent('import.sql', $sql);

        $this->actingAs($user)
            ->post(route('cabinet.profile.import.sql'), ['sql_file' => $file])
            ->assertRedirect();

        $this->assertDatabaseHas('categories_income', [
            'user_id' => $user->id,
            'name' => 'Фриланс',
        ]);
    }

    public function test_import_with_missing_table_section()
    {
        $user = User::factory()->create();

        $sql = <<<SQL
        INSERT INTO categories_income (name, created_at, updated_at) VALUES ('Зарплата', '2025-07-18 10:24:09', '2025-07-18 10:24:09');
        -- отсутсвует accounts и transactions
        INSERT INTO categories_expense (name, created_at, updated_at) VALUES ('Еда', '2025-07-18 10:24:09', '2025-07-18 10:24:09');
        SQL;

        $file = UploadedFile::fake()->createWithContent('partial.sql', $sql);

        $this->actingAs($user)
            ->post(route('cabinet.profile.import.sql'), ['sql_file' => $file])
            ->assertRedirect();

        $this->assertDatabaseHas('categories_income', ['user_id' => $user->id, 'name' => 'Зарплата']);
        $this->assertDatabaseHas('categories_expense', ['user_id' => $user->id, 'name' => 'Еда']);
    }

    public function test_import_fails_gracefully_on_malformed_sql()
    {
        $user = User::factory()->create();
        $invalidSql = "INSERT INTO unknown_table (id) VALUES (1"; // незакрытая скобка — синтаксическая ошибка

        $file = UploadedFile::fake()->createWithContent('invalid.sql', $invalidSql);

        $this->actingAs($user)
            ->post(route('cabinet.profile.import.sql'), ['sql_file' => $file])
            ->assertRedirect()
            ->assertSessionHas('error', 'Импорт не удался. См. лог.');
    }


    public function test_guest_cannot_import_data()
    {
        $file = UploadedFile::fake()->createWithContent('guest.sql', '');

        $this->post(route('cabinet.profile.import.sql'), ['sql_file' => $file])
            ->assertRedirect(route('login'));
    }

    public function test_import_ignores_transaction_with_missing_foreign_keys()
    {
        $user = User::factory()->create();

        $sql = <<<SQL
        INSERT INTO transactions (amount, type_id, category_id, account_id, created_at, updated_at)
        VALUES (100.00, 999, 999, 999, '2025-07-18 10:24:09', '2025-07-18 10:24:09');
        SQL;

        $file = UploadedFile::fake()->createWithContent('invalid_foreign.sql', $sql);

        $this->actingAs($user)
            ->post(route('cabinet.profile.import.sql'), ['sql_file' => $file])
            ->assertRedirect();

        $this->assertDatabaseMissing('transactions', ['amount' => 100.00]);
    }

}
