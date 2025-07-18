<?php

namespace Tests\Feature\Cabinet;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TransactionYearlyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_ok_and_correct_view()
    {
        $response = $this->get(route('yearlyStats', ['year' => now()->year]));
        $response->assertStatus(200);
        $response->assertViewIs('cabinet.stats.yearly_stats');
        $response->assertViewHas(['year', 'months']);
    }

    public function test_index_contains_correct_income_and_expense_data()
    {
        $year = now()->year;
        $month = 5;

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'type_id' => 1,
            'amount' => 150,
            'created_at' => Carbon::create($year, $month, 15),
        ]);

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'type_id' => 0,
            'amount' => 50,
            'created_at' => Carbon::create($year, $month, 20),
        ]);

        $response = $this->get(route('yearlyStats', ['year' => $year]));

        $response->assertStatus(200);
        $response->assertViewHas('months', function ($months) use ($month) {
            $m = $months->get($month - 1);
            return $m['income'] === 150.0 && $m['expense'] === 50.0 && $m['diff'] === 100.0;
        });
    }
}
