<?php

namespace App\Services\Cabinet\Statistics;

use App\Models\Transaction;
use App\Models\CategoriesIncome;
use App\Models\CategoriesExpense;
use App\Models\Account;
use App\Http\Requests\Cabinet\MonthlyStatsRequest;
use App\Services\Cabinet\StatsCacheService;
use Carbon\Carbon;

class MonthlyStatisticsService
{
    // генерация статистики доходов/расходов и графика
    public function getMonthlyStats(int $userId, Carbon $start, Carbon $end): array
    {
        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy(fn($t) => Carbon::parse($t->created_at)->format('d.m'));

        $dates = collect();
        $cursor = $start->copy();
        while ($cursor->month === $start->month) {
            $dates->push($cursor->format('d.m'));
            $cursor->addDay();
        }

        $incomeData = [];
        $expenseData = [];

        foreach ($dates as $date) {
            $daily = $transactions->get($date, collect());
            $income = $daily->where('type_id', 1)->sum('amount');
            $expense = $daily->where('type_id', 0)->sum('amount');
            $incomeData[] = round($income, 2);
            $expenseData[] = round($expense * -1, 2);
        }

        return [
            'dates' => $dates,
            'incomeData' => $incomeData,
            'expenseData' => $expenseData,
            'totalIncome' => $transactions->flatten()->where('type_id', 1)->sum('amount'),
            'totalExpense' => $transactions->flatten()->where('type_id', 0)->sum('amount'),
        ];
    }

    // Получение категорий и счетов пользователя
    public function getCategories(int $userId): array
    {
        return [
            'incomeCategories' => CategoriesIncome::where('user_id', $userId)->get(['id', 'name']),
            'expenseCategories' => CategoriesExpense::where('user_id', $userId)->get(['id', 'name']),
            'accounts' => Account::where('user_id', $userId)->get(['id', 'name']),
        ];
    }

    public function handle(MonthlyStatsRequest $request): array
    {
        $userId = auth()->id();
        $carbonMonth = $request->getMonth();

        $start = $carbonMonth->copy()->startOfMonth();
        $end = $carbonMonth->copy()->endOfMonth();

        $range = $start->format('Y-m');

        return app(StatsCacheService::class)->remember('monthly', $userId, $range, function () use ($userId, $start, $end, $carbonMonth) {
            return array_merge(
                $this->getMonthlyStats($userId, $start, $end),
                $this->getCategories($userId),
                [
                    'selectedMonth' => $carbonMonth->format('Y-m'),
                    'prevMonth' => $carbonMonth->copy()->subMonth()->format('Y-m'),
                    'nextMonth' => $carbonMonth->copy()->addMonth()->format('Y-m'),
                    'carbonMonth' => $carbonMonth,
                ]
            );
        });
    }
}
