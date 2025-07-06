<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionAllTimeController extends Controller
{

     public function index(Request $request)
    {
        $userId = auth()->id();

    $startYear = $request->input('start_year', now()->year - 9);
    $endYear = $startYear + 9;

    $years = collect();
    for ($year = $startYear; $year <= $endYear; $year++) {
        $start = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $end = $start->copy()->endOfYear();

        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $income = $transactions->where('type_id', 1)->sum('amount');
        $expense = $transactions->where('type_id', 0)->sum('amount');

        $years->push([
            'year' => $year,
            'income' => round($income, 2),
            'expense' => round($expense * -1, 2),
        ]);
    }

    $totalIncome = $years->sum('income');
    $totalExpense = $years->sum('expense');

    return view('cabinet.stats.all_time_stats', [
        'years' => $years,
        'startYear' => $startYear,
        'endYear' => $endYear,
        'totalIncome' => $totalIncome,
        'totalExpense' => $totalExpense,
    ]);
    }
}
