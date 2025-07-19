<?php

namespace App\Services\Cabinet;

use App\Models\Debt;
use App\Http\Requests\Cabinet\DebtRequest;
use App\Services\Cabinet\Currency\CurrencyRateService;

class DebtsService
{
    public function __construct(
        private CurrencyRateService $currencyRateService
    ) {}

    public function getMyDebts()
    {
        return Debt::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);
    }

    public function toggleStatus(Debt $debt): void
    {
        $debt->status = $debt->status == 1 ? 2 : 1;
        $debt->save();
    }

    public function store(DebtRequest $request): void
    {
        $usdRate = $this->currencyRateService->getUsdRate();

        Debt::create([
            'user_id'        => auth()->id(),
            'name'           => $request->name,
            'debt_direction' => $request->debt_direction,
            'contact_method' => $request->contact_method,
            'description'    => $request->description,
            'status'         => 1,
            'amount'         => round($request->amount / $usdRate, 2),
        ]);
    }

    public function update(DebtRequest $request, Debt $debt): void
    {
        $usdRate = $this->currencyRateService->getUsdRate();

        $debt->update([
            'name'           => $request->name,
            'debt_direction' => $request->debt_direction,
            'contact_method' => $request->contact_method,
            'description'    => $request->description,
            'amount'         => round($request->amount / $usdRate, 2),
        ]);
    }

    public function delete(Debt $debt): void
    {
        $debt->delete();
    }
}

