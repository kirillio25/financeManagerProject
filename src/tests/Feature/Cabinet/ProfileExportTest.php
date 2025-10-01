<?php

namespace Tests\Feature\Cabinet;

use App\Models\User;
use App\Models\CategoriesIncome;
use App\Models\CategoriesExpense;
use App\Models\Account;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProfileExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_sql_returns_streamed_response()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('profile.export.sql'));

        $response->assertOk();
        $response->assertHeader('Content-Disposition');
        $this->assertStringContainsString('-- SQL export for user ID', $response->streamedContent());
    }

    public function test_export_contains_user_data()
    {
        $user = User::factory()->create();

        CategoriesIncome::factory()->create(['user_id' => $user->id, 'name' => 'Зарплата']);
        CategoriesExpense::factory()->create(['user_id' => $user->id, 'name' => 'Еда']);
        $account = Account::factory()->create(['user_id' => $user->id, 'name' => 'Наличные']);
        Debt::factory()->create(['user_id' => $user->id, 'name' => 'Займ']);
        Transaction::factory()->create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => 1,
            'type_id' => 1,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('profile.export.sql'));

        $content = $response->streamedContent();

        $this->assertStringContainsString("INSERT INTO categories_income", $content);
        $this->assertStringContainsString("INSERT INTO categories_expense", $content);
        $this->assertStringContainsString("INSERT INTO accounts", $content);
        $this->assertStringContainsString("INSERT INTO debts", $content);
        $this->assertStringContainsString("INSERT INTO transactions", $content);
    }

    public function test_guest_cannot_export_sql()
    {
        $this->get(route('profile.export.sql'))->assertRedirect('/login');
    }
}
