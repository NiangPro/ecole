<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormationProgress>
 */
class FormationProgressFactory extends Factory
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
            'formation_slug' => fake()->randomElement(['html5', 'css3', 'javascript', 'php', 'python']),
            'section_id' => fake()->randomElement(['intro', 'basic', 'advanced']),
            'progress_percentage' => fake()->numberBetween(0, 100),
            'started_at' => now(),
            'completed_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'completed_sections' => [],
            'time_spent_minutes' => fake()->numberBetween(0, 500),
        ];
    }
}
