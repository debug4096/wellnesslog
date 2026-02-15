<?php

namespace Database\Factories;

use App\Models\DailyEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailyEntry>
 */
class DailyEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'date'          => fake()->unique()->dateTimeBetween('-30 days')->format('Y-m-d'),
            'mood_level'    => fake()->numberBetween(1, 10),
            'energy_level'  => fake()->numberBetween(1, 10),
            'sleep_minutes' => fake()->numberBetween(60, 540),
            'notes'         => fake()->optional(0.7)->sentence(),
        ];
    }
}
