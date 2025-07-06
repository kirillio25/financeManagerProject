<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'type_id' => $this->faker->randomElement([0, 1]),
            'category_id' => 0, // или CategoryIncome/Expense::factory() в зависимости от type_id
            'account_id' => Account::factory(),
        ];
    }
}
