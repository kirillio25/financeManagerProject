<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesIncomeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories_income')->insert([
            [
                'user_id' => 1,
                'name' => 'Зарплата',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'Фриланс',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'Подарки',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
