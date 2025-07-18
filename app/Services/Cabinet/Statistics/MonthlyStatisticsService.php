<?php

namespace App\Services\Cabinet\Statistics;

use App\Models\Transaction;
use App\Models\CategoriesIncome;
use App\Models\CategoriesExpense;
use App\Models\Account;
use App\Http\Requests\Cabinet\MonthlyStatsRequest;
use App\DTOs\Cabinet\MonthlyStatsDTO;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MonthlyStatisticsService
{
    // Метод генерации статистики доходов/расходов и графика
    public function getMonthlyStats(int $userId, MonthlyStatsDTO $dto): array
    {
        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$dto->start, $dto->end])
            ->get()
            ->groupBy(fn($t) => Carbon::parse($t->created_at)->format('d.m'));

        $dates = collect();
        $cursor = $dto->start->copy();
        while ($cursor->month === $dto->start->month) {
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
        $dto = MonthlyStatsDTO::fromCarbonMonth($carbonMonth);

        return array_merge(
            $this->getMonthlyStats($userId, $dto),
            $this->getCategories($userId),
            [
                'selectedMonth' => $dto->selectedMonth,
                'prevMonth' => $dto->prevMonth,
                'nextMonth' => $dto->nextMonth,
                'carbonMonth' => $carbonMonth,
            ]
        );
    }
}
