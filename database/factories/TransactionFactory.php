<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Link to User factory
            'type' => $this->faker->randomElement(['funding', 'purchase']),
            'amount' => $this->faker->randomFloat(2, 1, 100),
            'description' => $this->faker->sentence,
        ];
    }
}
