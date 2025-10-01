<?php

namespace Tests\Feature\Cabinet;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionHistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_transaction_history()
    {
        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'type_id' => 1,
            'amount' => 100,
        ]);

        $response = $this->get(route('transactionHistory.index'));

        $response->assertStatus(200);
        $response->assertViewIs('cabinet.other.transaction-history');
        $response->assertViewHas('transactions');
    }

    public function test_destroy_deletes_transaction()
    {
        $transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->delete('/transactionHistory/' . $transaction->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    }
}
