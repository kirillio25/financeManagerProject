<?php

namespace Database\Factories;

use App\Models\CategoriesExpense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriesExpenseFactory extends Factory
{
    protected $model = CategoriesExpense::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
        ];
    }
}
