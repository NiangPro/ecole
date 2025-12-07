<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecurityAudit>
 */
class SecurityAuditFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_type' => fake()->randomElement(array_keys(\App\Models\SecurityAudit::EVENT_TYPES)),
            'severity' => fake()->randomElement([
                \App\Models\SecurityAudit::SEVERITY_LOW,
                \App\Models\SecurityAudit::SEVERITY_MEDIUM,
                \App\Models\SecurityAudit::SEVERITY_HIGH,
                \App\Models\SecurityAudit::SEVERITY_CRITICAL,
            ]),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'user_id' => null,
            'route' => fake()->randomElement(['admin.login', 'dashboard.index', 'home']),
            'method' => fake()->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'request_data' => [],
            'response_code' => fake()->randomElement([200, 403, 404, 419, 429, 500]),
            'message' => fake()->sentence(),
            'metadata' => [],
        ];
    }
}
