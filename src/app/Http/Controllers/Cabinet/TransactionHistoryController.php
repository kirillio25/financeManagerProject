<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Cabinet\Transaction\TransactionHistoryService;

class TransactionHistoryController extends Controller
{
    public function index(TransactionHistoryService $service)
    {
        $transactions = $service->getPaginatedUserTransactions();
        return view('cabinet.other.transaction-history', compact('transactions'));
    }

    public function destroy(Transaction $transaction, TransactionHistoryService $service)
    {
        $service->deleteTransaction($transaction);
        return redirect()->back()->with('success', 'Транзакция удалена.');
    }
}
