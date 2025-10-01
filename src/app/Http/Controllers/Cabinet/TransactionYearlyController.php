<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Cabinet\Statistics\YearlyStatisticsService;
use Illuminate\Http\Request;

class TransactionYearlyController extends Controller
{
    public function index(Request $request, YearlyStatisticsService $service)
    {
        return view('cabinet.stats.yearly_stats', $service->handle($request));
    }
}
