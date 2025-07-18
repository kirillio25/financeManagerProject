<?php

namespace Tests\Unit\Cabinet;

use Tests\TestCase;
use App\Models\User;
use App\Models\Debt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DebtServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_sees_only_own_debts()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Debt::factory()->create([
            'user_id' => $user->id,
            'name' => 'Долг 1',
            'debt_direction' => 1,
            'amount' => 100,
            'status' => 1,
        ]);

        Debt::factory()->create([
            'user_id' => $user->id,
            'name' => 'Долг 2',
            'debt_direction' => 2,
            'amount' => 200,
            'status' => 1,
        ]);

        Debt::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Чужой долг',
            'debt_direction' => 1,
            'amount' => 300,
            'status' => 1,
        ]);

        $this->actingAs($user);

        $debts = Debt::where('user_id', $user->id)->get();

        $this->assertCount(2, $debts);
        $this->assertTrue($debts->pluck('name')->contains('Долг 1'));
        $this->assertTrue($debts->pluck('name')->contains('Долг 2'));
        $this->assertFalse($debts->pluck('name')->contains('Чужой долг'));
    }
}
