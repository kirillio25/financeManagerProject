<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Requests\Cabinet\MonthlyStatsRequest;
use App\Services\Cabinet\Statistics\MonthlyStatisticsService;
use App\Http\Requests\Cabinet\StoreTransactionRequest;
use App\Services\Cabinet\Transaction\TransactionMonthlyService;
use App\Http\Controllers\Controller;


class TransactionMonthlyController extends Controller
{
    public function index(MonthlyStatsRequest $request, MonthlyStatisticsService $service)
    {
        return view('cabinet.stats.monthly_stats', $service->handle($request));
    }

    public function store(StoreTransactionRequest $request, TransactionMonthlyService $service)
    {
        $service->handle($request);
        return back();
    }
}
