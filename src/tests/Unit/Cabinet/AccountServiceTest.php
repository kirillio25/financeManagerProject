<?php

namespace Tests\Unit\Cabinet;

use Tests\TestCase;
use App\Models\Account;
use App\Models\Transaction;
use App\Services\Cabinet\AccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_accounts_with_balance_returns_correct_data()
    {
        $account = Account::factory()->create();
        Transaction::factory()->create(['account_id' => $account->id, 'type_id' => 1, 'amount' => 100]); // доход
        Transaction::factory()->create(['account_id' => $account->id, 'type_id' => 0, 'amount' => 30]);  // расход

        $service = new AccountService();
        $accounts = $service->getAccountsWithBalance();

        $this->assertCount(1, $accounts);
        $this->assertEquals(70, $accounts->first()['balance']);
    }
}
