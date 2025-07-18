<?php

namespace App\Services\Cabinet\Transaction;

use App\Models\Transaction;
use App\Http\Requests\Cabinet\StoreTransactionRequest;
use App\Services\Cabinet\Currency\CurrencyRateService;
use Illuminate\Support\Carbon;

class TransactionMonthlyService
{
    public function __construct(
        private CurrencyRateService $currencyRateService
    ) {}

    public function store(array $data): void
    {
        Transaction::create([
            'user_id'    => $data['user_id'],
            'amount'     => $data['amount'],
            'type_id'    => $data['type_id'],
            'category_id'=> $data['category_id'],
            'account_id' => $data['account_id'],
            'created_at' => Carbon::parse($data['date'])->setTimeFrom(now()),
            'updated_at' => now(),
        ]);
    }

    public function handle(StoreTransactionRequest $request): void
    {
        $usdRate = $this->currencyRateService->getUsdRate();

        $this->store([
            'user_id'     => auth()->id(),
            'amount'      => round($request->amount / $usdRate, 2),
            'type_id'     => $request->type_id,
            'category_id' => $request->category_id,
            'account_id'  => $request->account_id,
            'date'        => $request->date,
        ]);
    }
}
