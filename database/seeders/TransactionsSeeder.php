<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TransactionsSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;

        $incomeCategories = DB::table('categories_income')->pluck('id')->toArray();
        $expenseCategories = DB::table('categories_expense')->pluck('id')->toArray();
        $accounts = DB::table('accounts')->pluck('id')->toArray();

        $startDate = Carbon::now()->subYears(3)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $batchSize = 5000;
        $transactions = [];

        while ($startDate <= $endDate) {
            foreach (range(1, rand(1, 5)) as $i) {
                $transactions[] = [
                    'user_id' => $userId,
                    'amount' => rand(1000, 10000) / 100,
                    'type_id' => 1,
                    'category_id' => $incomeCategories[array_rand($incomeCategories)],
                    'account_id' => $accounts[array_rand($accounts)],
                    'created_at' => $startDate->copy()->setTime(rand(6, 11), rand(0, 59)),
                    'updated_at' => now(),
                ];
            }

            foreach (range(1, rand(1, 5)) as $i) {
                $transactions[] = [
                    'user_id' => $userId,
                    'amount' => rand(1000, 10000) / 100,
                    'type_id' => 0,
                    'category_id' => $expenseCategories[array_rand($expenseCategories)],
                    'account_id' => $accounts[array_rand($accounts)],
                    'created_at' => $startDate->copy()->setTime(rand(12, 23), rand(0, 59)),
                    'updated_at' => now(),
                ];
            }

            // Вставка батчами
            if (count($transactions) >= $batchSize) {
                DB::table('transactions')->insert($transactions);
                $transactions = [];
            }

            $startDate->addDay();
        }

        // Остаток
        if (!empty($transactions)) {
            DB::table('transactions')->insert($transactions);
        }
    }
}
