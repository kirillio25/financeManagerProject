<?php

namespace Tests\Feature\Cabinet;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TransactionAllTimeControllerTest extends TestCase
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
        $response = $this->get(route('allTimeStats'));
        $response->assertStatus(200);
        $response->assertViewIs('cabinet.stats.all_time_stats');
        $response->assertViewHas(['years', 'startYear', 'endYear', 'totalIncome', 'totalExpense']);
    }

    public function test_index_returns_correct_data_for_year()
    {
        $year = now()->year - 3;

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'type_id' => 1,
            'amount' => 500,
            'created_at' => Carbon::create($year, 6, 1),
        ]);

        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'type_id' => 0,
            'amount' => 200,
            'created_at' => Carbon::create($year, 7, 1),
        ]);

        $response = $this->get(route('allTimeStats', ['start_year' => $year]));

        $response->assertStatus(200);
        $response->assertViewHas('years', function ($years) use ($year) {
            $entry = collect($years)->firstWhere('year', $year);
            return $entry &&
                $entry['income'] === 500.0 &&
                $entry['expense'] === -200.0;
        });
    }
}
