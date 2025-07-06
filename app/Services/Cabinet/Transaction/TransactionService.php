<?php 

namespace App\Services\Cabinet\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Carbon;

class TransactionService
{
    public function store(array $data): void
    {
        Transaction::create([
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'type_id' => $data['type_id'],
            'category_id' => $data['category_id'],
            'account_id' => $data['account_id'],
            'created_at' => Carbon::parse($data['date']),
            'updated_at' => now(),
        ]);
    }
}
