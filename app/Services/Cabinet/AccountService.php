<?php 

namespace App\Services\Cabinet;

use App\Models\Account;

class AccountService
{
    public function getAccountsWithBalance()
    {
        return Account::with('transactions')->get()->map(function ($account) {
            $balance = $account->transactions->reduce(function ($carry, $transaction) {
                return $carry + ($transaction->type_id === 1 ? $transaction->amount : -$transaction->amount);
            }, 0);

            return [
                'id' => $account->id,
                'name' => $account->name,
                'note' => $account->note,
                'balance' => $balance,
            ];
        });
    }
}
