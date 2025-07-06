<?php

namespace App\Http\Controllers\Cabinet;

use App\Models\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionYearlyController extends Controller
{
public function index(Request $request)
    {
        $userId = auth()->id();
        $year = (int) $request->input('year', now()->year);

        $months = collect(range(1, 12))->map(function ($month) use ($year, $userId) {
            $start = Carbon::create($year, $month)->startOfMonth();
            $end = Carbon::create($year, $month)->endOfMonth();

            $income = Transaction::where('user_id', $userId)
                ->whereBetween('created_at', [$start, $end])
                ->where('type_id', 1)
                ->sum('amount');

            $expense = Transaction::where('user_id', $userId)
                ->whereBetween('created_at', [$start, $end])
                ->where('type_id', 0)
                ->sum('amount');

            return [
                'month' => $start->translatedFormat('F'),
                'income' => round($income, 2),
                'expense' => round($expense, 2),
                'diff' => round($income - $expense, 2),
            ];
        });

        return view('cabinet.stats.yearly_stats', [
            'year' => $year,
            'months' => $months,
        ]);
    }

}
