<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DebtsSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;
        $startDate = Carbon::now()->subDays(14)->startOfDay(); // последние 15 дней
        $endDate = Carbon::now()->endOfDay();

        $names = ['Иван', 'Мария', 'Антон', 'Ольга', 'Пётр', 'Светлана'];
        $methods = [
            '+77011234567',
            '+79601234567',
            'user@example.com',
            'second@mail.ru',
            '@user123',
            '@contact_bot',
            null,
        ];
        $statuses = [0, 1, 2]; // Пример: 0 — активен, 1 — частично, 2 — закрыт

        $debts = [];

        while ($startDate <= $endDate) {
            foreach (range(1, rand(1, 3)) as $i) {
                $debts[] = [
                    'user_id' => $userId,
                    'debt_direction' => rand(0, 1),
                    'name' => $names[array_rand($names)],
                    'amount' => rand(1000, 10000) / 100,
                    'contact_method' => $methods[array_rand($methods)],
                    'description' => fake()->sentence(),
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => $startDate->copy()->setTime(rand(9, 21), rand(0, 59)),
                    'updated_at' => now(),
                ];
            }

            $startDate->addDay();
        }

        DB::table('debts')->insert($debts);
    }
}
