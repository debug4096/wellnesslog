<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'email' => 'demo@wellnesslog.test',
        ]);

        $this->call([
            DailyEntrySeeder::class,
            MedicationSeeder::class,
            MedicationLogSeeder::class,
        ]);
    }
}
