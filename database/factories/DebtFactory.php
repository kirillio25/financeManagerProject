<?php

namespace Database\Factories;

use App\Models\Debt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebtFactory extends Factory
{
    protected $model = Debt::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'debt_direction' => $this->faker->numberBetween(1, 2),
            'name' => $this->faker->words(2, true),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'contact_method' => $this->faker->optional()->email(),
            'description' => $this->faker->optional()->sentence(),
            'status' => $this->faker->numberBetween(1, 2),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
