<?php

namespace App\Http\Controllers\Cabinet;

use App\Services\Cabinet\Currency\CurrencyRateService;


use App\Http\Requests\Cabinet\MonthlyStatsRequest;
use App\Services\Cabinet\Statistics\MonthlyStatisticsService;
use App\DTOs\Cabinet\MonthlyStatsDTO;

use App\Http\Requests\Cabinet\StoreTransactionRequest;
use App\Services\Cabinet\Transaction\TransactionService;


use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class TransactionMonthlyController extends Controller
{
    // Метод показа статистики по месяцу
    public function index(MonthlyStatsRequest $request, MonthlyStatisticsService $service)
    {
        $userId = auth()->id();

        // Получаем Carbon-объект выбранного месяца из запроса
        $carbonMonth = $request->getMonth();

        // Генерируем DTO для периода
        $dto = MonthlyStatsDTO::fromCarbonMonth($carbonMonth);

        // Получаем статистику доходов/расходов
        $stats = $service->getMonthlyStats($userId, $dto);

        // Получаем категории и счета пользователя
        $categories = $service->getCategories($userId);

        // Объединяем всё и передаём в представление
        return view('cabinet.stats.monthly_stats', array_merge(
            $stats,
            $categories,
            [
                'selectedMonth' => $dto->selectedMonth,
                'prevMonth' => $dto->prevMonth,
                'nextMonth' => $dto->nextMonth,
                'carbonMonth' => $carbonMonth,
            ]
        ));
    }


    public function store(
        StoreTransactionRequest $request,
        CurrencyRateService $rateService,
        TransactionService $transactionService
    ) {
        $usdRate = $rateService->getUsdRate();

        $transactionService->store([
            'user_id' => auth()->id(),
            'amount' => round($request->amount / $usdRate, 2),
            'type_id' => $request->type_id,
            'category_id' => $request->category_id,
            'account_id' => $request->account_id,
            'date' => $request->date,
        ]);

        return redirect()->back();
    }
}
