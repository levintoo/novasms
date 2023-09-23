<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
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
            'group_id' => rand(0,1) === 1 ? 1 : null,
            'recipient' => fake()->e164PhoneNumber(),
            'content' => fake()->sentence(),
            'delivered_at' => rand(0,1) === 1 ? fake()->dateTime() : null,
            'message_id' => fake()->iosMobileToken()
        ];
    }
}
