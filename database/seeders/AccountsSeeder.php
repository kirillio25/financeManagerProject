<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('accounts')->insert([
            [
                'user_id' => 1,
                'name' => 'Наличные',
                'note' => 'Основной кошелёк',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'Карта',
                'note' => 'Банковская карта',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
