<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('id', 1)->exists()) {
            User::factory()->create([
                'id' => 1,
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password123'),
            ]);
        }

        DB::table('debts')->truncate();
        DB::table('transactions')->truncate();
        DB::table('categories_income')->truncate();
        DB::table('categories_expense')->truncate();
        DB::table('accounts')->truncate();

        $this->call([
            CategoriesExpenseSeeder::class,
            CategoriesIncomeSeeder::class,
            AccountsSeeder::class,
            TransactionsSeeder::class,
            DebtsSeeder::class,
        ]);
    }
}
