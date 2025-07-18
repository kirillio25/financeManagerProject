<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\Cabinet\Statistics\AllTimeStatisticsService;
use Illuminate\Http\Request;

class TransactionAllTimeController extends Controller
{
    public function index(Request $request, AllTimeStatisticsService $service)
    {
        return view('cabinet.stats.all_time_stats', $service->handle($request));
    }
}
