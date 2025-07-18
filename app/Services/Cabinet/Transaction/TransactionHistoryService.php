<?php

namespace App\Services\Cabinet\Transaction;

use App\Models\Transaction;

class TransactionHistoryService
{
    public function getUserTransactions(): array
    {
        return Transaction::with(['account', 'categoryIncome', 'categoryExpense'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'type' => $transaction->type_id === 1 ? 'доход' : 'расход',
                    'category' => $transaction->type_id === 1
                        ? optional($transaction->categoryIncome)->name
                        : optional($transaction->categoryExpense)->name,
                    'account' => optional($transaction->account)->name,
                    'date' => $transaction->created_at->format('Y-m-d H:i'),
                ];
            })
            ->toArray();
    }
}
