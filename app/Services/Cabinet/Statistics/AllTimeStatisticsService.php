<?php

namespace App\Services\Cabinet\Statistics;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AllTimeStatisticsService
{
    public function handle(Request $request): array
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

        return [
            'years' => $years,
            'startYear' => $startYear,
            'endYear' => $endYear,
            'totalIncome' => $years->sum('income'),
            'totalExpense' => $years->sum('expense'),
        ];
    }
}
