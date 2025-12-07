<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'type' => fake()->randomElement(['info', 'success', 'warning', 'error']),
            'title' => fake()->sentence(3),
            'message' => fake()->sentence(),
            'link' => fake()->optional()->url(),
            'is_read' => false,
        ];
    }
}
