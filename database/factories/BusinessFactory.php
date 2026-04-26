<?php

namespace Database\Factories;

use App\Enums\BusinessStatus;
use App\Enums\BusinessType;
use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'state' => $this->faker->state(),
            'name' => $this->faker->company().' '.$this->faker->companySuffix(),
            'type' => BusinessType::Llc,
            'purpose' => $this->faker->paragraph(),
            'status' => BusinessStatus::Submitted,
            'use_registrar_agent' => true,
            'submitted_at' => now(),
        ];
    }

    /**
     * Indicate that the business filing has been approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BusinessStatus::Approved,
        ]);
    }

    /**
     * Indicate that the business filing has been rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BusinessStatus::Rejected,
        ]);
    }

    /**
     * Indicate that the business filing is under review.
     */
    public function underReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BusinessStatus::UnderReview,
        ]);
    }
}
