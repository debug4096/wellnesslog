<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MedicationLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class MedicationLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@wellnesslog.test')
            ->firstOrFail();
        $medications = $user->medications;

        foreach ($medications as $medication) {
            MedicationLog::factory()
                ->count(20)
                ->for($medication)
                ->create();
        }
    }
}
