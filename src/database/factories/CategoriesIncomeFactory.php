<?php

namespace Database\Factories;

use App\Models\CategoriesIncome;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriesIncomeFactory extends Factory
{
    protected $model = CategoriesIncome::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
        ];
    }
}
