<?php

namespace Database\Factories;

use App\Enums\MedicationFrequency;
use App\Enums\MedicationUnit;
use App\Models\Medication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medication>
 */
class MedicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'   => User::factory(),
            'name'      => fake()->randomElement([
                'Ibuprofen',
                'Aspirin',
                'Paracetamol',
                'Vitamin D',
                'Omeprazole',
                'Metformin',
                'Amoxicillin',
                'Cetirizine',
                'Melatonin',
                'Magnesium',
                'Sertraline',
                'Lisinopril',
            ]),
            'dosage'    => fake()->randomElement([0.5, 1, 2, 5, 10, 25, 50, 100, 200, 400, 500]),
            'unit'      => fake()->randomElement(MedicationUnit::cases()),
            'frequency' => fake()->randomElement(MedicationFrequency::cases()),
        ];
    }

    public function asNeeded(): static
    {
        return $this->state(fn() => [
            'frequency' => MedicationFrequency::AsNeeded,
        ]);
    }

    public function trashed(): static
    {
        return $this->state(fn() => [
            'deleted_at' => now()->subDays(30),
        ]);
    }
}
