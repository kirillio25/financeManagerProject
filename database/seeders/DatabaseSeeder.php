<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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

        $this->call([
            CategoriesExpenseSeeder::class,
            CategoriesIncomeSeeder::class,
            AccountsSeeder::class,
        ]);
    }
}
