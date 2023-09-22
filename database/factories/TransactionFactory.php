<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'amount' => rand(100,100000),
            'recipient' => fake()->e164PhoneNumber(),
            'status' => rand(0,1),
            'type' => rand(0,1),
            'transaction_id' => fake()->uuid(),
        ];
    }
}
