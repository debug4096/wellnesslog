<?php

namespace Database\Factories;

use App\Models\Medication;
use App\Models\MedicationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicationLog>
 */
class MedicationLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'medication_id' => Medication::factory(),
            'user_id'       => fn(array $attributes) => Medication::find($attributes['medication_id'])->user_id,
            'taken_at'      => fake()->dateTimeBetween('-30 days'),
            'dosage'        => fake()->randomElement([0.5, 1, 2, 5, 10, 25, 50, 100, 200, 400, 500]),
            'notes'         => fake()->optional(0.7)->sentence(),
        ];
    }
}
