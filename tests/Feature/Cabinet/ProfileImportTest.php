<?php

namespace Tests\Feature\Cabinet;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use App\Models\CategoriesIncome;
use App\Models\CategoriesExpense;
use App\Models\Debt;
use App\Models\Transaction;

class ProfileSqlImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    protected function importFromSql(string $sql): void
    {
        $file = UploadedFile::fake()->createWithContent('import.sql', $sql);

        $this->post(route('cabinet.profile.import.sql'), [
            'sql_file' => $file,
        ])->assertRedirect();
    }

    public function test_import_skips_duplicates()
    {
        $sql = <<<SQL
        INSERT INTO categories_income (name, created_at, updated_at) VALUES ('Зарплата', now(), now());
        INSERT INTO categories_income (name, created_at, updated_at) VALUES ('Зарплата', now(), now());
        SQL;

        $this->importFromSql($sql);

        $this->assertEquals(1, CategoriesIncome::where('user_id', $this->user->id)->where('name', 'Зарплата')->count());
    }

    public function test_import_saves_all_valid_data()
    {
        $sql = <<<SQL
        INSERT INTO accounts (name, note, created_at, updated_at) VALUES ('Карта', 'Visa', now(), now());
        INSERT INTO categories_expense (name, created_at, updated_at) VALUES ('Продукты', now(), now());
        INSERT INTO debts (debt_direction, name, amount, contact_method, description, status, created_at, updated_at)
        VALUES (0, 'Друг', 500.00, 'Телефон', 'описание', 1, now(), now());
        SQL;

        $this->importFromSql($sql);

        $this->assertDatabaseHas('accounts', ['name' => 'Карта', 'user_id' => $this->user->id]);
        $this->assertDatabaseHas('categories_expense', ['name' => 'Продукты', 'user_id' => $this->user->id]);
        $this->assertDatabaseHas('debts', ['name' => 'Друг', 'user_id' => $this->user->id]);
    }

    public function test_import_partial_tables()
    {
        $sql1 = <<<SQL
        INSERT INTO accounts (name, note, created_at, updated_at) VALUES ('Наличные', 'Кошелек', now(), now());
        SQL;

        $sql2 = <<<SQL
        INSERT INTO categories_income (name, created_at, updated_at) VALUES ('Фриланс', now(), now());
        INSERT INTO transactions (amount, type_id, category_id, account_id, created_at, updated_at)
        VALUES (100.00, 1, 0, 0, now(), now());
        SQL;

        $this->importFromSql($sql1);
        $this->assertDatabaseHas('accounts', ['name' => 'Наличные', 'user_id' => $this->user->id]);

        $this->importFromSql($sql2);
        $this->assertDatabaseHas('categories_income', ['name' => 'Фриланс', 'user_id' => $this->user->id]);
        $this->assertEquals(1, Transaction::where('user_id', $this->user->id)->count());
    }
}
