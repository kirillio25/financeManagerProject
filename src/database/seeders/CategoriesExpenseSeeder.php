<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesExpenseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories_expense')->insert([
            [
                'user_id' => 1,
                'name' => 'Продукты',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'Транспорт',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'Развлечения',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
