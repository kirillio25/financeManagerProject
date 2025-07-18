<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\CategoriesIncome;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TransactionMonthlyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_ok()
    {
        $response = $this->get(route('monthlyStats.index', ['month' => now()->format('Y-m')]));
        $response->assertStatus(200);
        $response->assertViewIs('cabinet.stats.monthly_stats');
    }

    public function test_store_creates_transaction()
    {
        $account = Account::factory()->create(['user_id' => $this->user->id]);
        $сategoriyIncome = CategoriesIncome::factory()->create(['user_id' => $this->user->id]);

        $response = $this->post(route('monthlyStats.store'), [
            'amount' => 100,
            'type_id' => 0,
            'category_id' => $сategoriyIncome->id,
            'account_id' => $account->id,
            'date' => now()->toDateString(),
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'category_id' => $сategoriyIncome->id,
            'account_id' => $account->id,
        ]);
    }
}
