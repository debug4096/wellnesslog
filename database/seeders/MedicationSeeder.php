<?php

namespace Database\Seeders;

use App\Models\Medication;
use App\Models\User;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@wellnesslog.test')
            ->firstOrFail();

        Medication::factory()
            ->count(4)
            ->for($user)
            ->create();

        Medication::factory()
            ->count(2)
            ->for($user)
            ->create([
                'deleted_at' => now()->subDays(30),
            ]);
    }
}
