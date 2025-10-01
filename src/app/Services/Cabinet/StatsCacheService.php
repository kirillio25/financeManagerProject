<?php

namespace App\Services\Cabinet;

use App\Services\Cabinet\Statistics\AllTimeStatisticsService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class StatsCacheService
{
    public function remember(string $prefix, int $userId, string $range, \Closure $callback): array
    {
        $cacheKey = $range
            ? "{$prefix}_stats:{$userId}:{$range}"
            : "{$prefix}_stats:{$userId}";

        return Cache::rememberForever($cacheKey, $callback);
    }


     // Обновляет/создаёт кэш если нужно month/year/all-time
    public function updateCaches(int $userId, Carbon $date, float $amount, int $typeId): void
    {
        $monthKey   = "monthly_stats:{$userId}:{$date->format('Y-m')}";
        $yearKey    = "yearly_stats:{$userId}:{$date->year}";
        $allTimeKey = "all_time_stats:{$userId}";

        // Monthly
        if (Cache::has($monthKey)) {
            $monthly = Cache::get($monthKey);

            $day = $date->format('d.m');
            $dates = $monthly['dates'] instanceof Collection ? $monthly['dates'] : collect($monthly['dates']);
            $index = $dates->search($day);

            if ($index !== false && $index !== null) {
                $monthly['incomeData'][$index] = ($monthly['incomeData'][$index] ?? 0) + ($typeId === 1 ? $amount : 0);
                $monthly['expenseData'][$index] = ($monthly['expenseData'][$index] ?? 0) + ($typeId === 0 ? -$amount : 0);
                $monthly['totalIncome'] = ($monthly['totalIncome'] ?? 0) + ($typeId === 1 ? $amount : 0);
                $monthly['totalExpense'] = ($monthly['totalExpense'] ?? 0) + ($typeId === 0 ? $amount : 0);
                $monthly['dates'] = $dates;

                Cache::forever($monthKey, $monthly);
            }
        } else {
            try {
                $monthlyService = app(\App\Services\Cabinet\Statistics\MonthlyStatisticsService::class);

                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();

                $monthlyData = $monthlyService->getMonthlyStats($userId, $start, $end);
                $categories = $monthlyService->getCategories($userId);

                $full = array_merge(
                    $monthlyData,
                    $categories,
                    [
                        'selectedMonth' => $date->format('Y-m'),
                        'prevMonth' => $date->copy()->subMonth()->format('Y-m'),
                        'nextMonth' => $date->copy()->addMonth()->format('Y-m'),
                        'carbonMonth' => $date,
                    ]
                );

                Cache::forever($monthKey, $full);
            } catch (\Throwable $e) {
                Log::error('failed to create monthly cache on updateCaches', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Yearly
        if (Cache::has($yearKey)) {
            $yearly = Cache::get($yearKey);

            $monthName = mb_strtolower($date->translatedFormat('F'));
            $months = $yearly['months'] instanceof Collection ? $yearly['months'] : collect($yearly['months']);
            $found = false;

            $months = $months->map(function ($m) use ($monthName, $amount, $typeId, &$found) {
                $mNameLower = mb_strtolower($m['month'] ?? '');
                if ($mNameLower === $monthName) {
                    if ($typeId === 1) {
                        $m['income'] = ($m['income'] ?? 0) + $amount;
                    } else {
                        $m['expense'] = ($m['expense'] ?? 0) + $amount;
                    }
                    $m['diff'] = round(($m['income'] ?? 0) - ($m['expense'] ?? 0), 2);
                    $found = true;
                }
                return $m;
            });

            if (! $found) {
                $months->push([
                    'month' => ucfirst($date->translatedFormat('F')),
                    'income' => $typeId === 1 ? $amount : 0,
                    'expense' => $typeId === 0 ? $amount : 0,
                    'diff' => round(($typeId === 1 ? $amount : 0) - ($typeId === 0 ? $amount : 0), 2),
                ]);
            }

            $yearly['months'] = $months;
            $yearly['totalIncome'] = $months->sum('income');
            $yearly['totalExpense'] = $months->sum('expense');

            Cache::forever($yearKey, $yearly);
        } else {
            try {
                $req = Request::create('/', 'GET', ['year' => $date->year]);
                $yearlyData = app(\App\Services\Cabinet\Statistics\YearlyStatisticsService::class)->handle($req);
                Cache::forever($yearKey, $yearlyData);
            } catch (\Throwable $e) {
                Log::error('failed to create yearly cache on updateCaches', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // All-time
        if (Cache::has($allTimeKey)) {
            $allTime = Cache::get($allTimeKey);

            $years = $allTime['years'] instanceof Collection ? $allTime['years'] : collect($allTime['years']);
            $found = false;

            $years = $years->map(function ($y) use ($date, $amount, $typeId, &$found) {
                if ((int) ($y['year'] ?? 0) === (int) $date->year) {
                    if ($typeId === 1) {
                        $y['income'] = ($y['income'] ?? 0) + $amount;
                    } else {
                        $y['expense'] = ($y['expense'] ?? 0) + $amount;
                    }
                    $found = true;
                }
                return $y;
            });

            if (! $found) {
                $years->push([
                    'year' => (int) $date->year,
                    'income' => $typeId === 1 ? $amount : 0,
                    'expense' => $typeId === 0 ? $amount : 0,
                ]);
            }

            $allTime['years'] = $years->sortBy('year')->values();
            $allTime['totalIncome'] = $years->sum('income');
            $allTime['totalExpense'] = $years->sum('expense');
            $allTime['startYear'] = $years->min('year');
            $allTime['endYear'] = $years->max('year');

            Cache::forever($allTimeKey, $allTime);
        } else {
            try {
                $req = Request::create('/');
                $allTimeData = app(AllTimeStatisticsService::class)->handle($req);
                Cache::forever($allTimeKey, $allTimeData);
            } catch (\Throwable $e) {
                Log::error('failed to create all_time cache on updateCaches', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public function decrementCaches(int $userId, Carbon $date, float $amount, int $typeId): void
    {
        $this->updateCaches($userId, $date, -$amount, $typeId);
    }
}
