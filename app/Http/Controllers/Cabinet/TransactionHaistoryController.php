<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Cabinet\Transaction\TransactionHistoryService;

class TransactionHaistoryController extends Controller
{
    public function index(TransactionHistoryService $service)
    {
        $transactions = $service->getUserTransactions();
        return view('cabinet.other.transaction-history', compact('transactions'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->back()->with('success', 'Транзакция удалена.');
    }
}
