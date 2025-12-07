<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
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
            'favoritable_type' => fake()->randomElement(['formation', 'article']),
            'favoritable_slug' => fake()->slug(),
            'favoritable_name' => fake()->words(2, true),
        ];
    }
}
