<?php

namespace App\Services\Cabinet\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use App\Services\Cabinet\StatsCacheService;

class TransactionHistoryService
{
    protected StatsCacheService $cacheService;

    public function __construct(StatsCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }
    public function getPaginatedUserTransactions(): LengthAwarePaginator
    {
        return Transaction::with(['account', 'categoryIncome', 'categoryExpense'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20)
            ->through(function ($transaction) {
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
            });
    }

    public function deleteTransaction(Transaction $transaction): void
    {
        $userId = $transaction->user_id;
        $date   = $transaction->date instanceof Carbon
            ? $transaction->date
            : Carbon::parse($transaction->date);
        $amount = $transaction->amount;
        $typeId = $transaction->type_id;

        $transaction->delete();
        $this->cacheService->decrementCaches($userId, $date, $amount, $typeId);
    }
}
