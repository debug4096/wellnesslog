<?php

namespace Database\Seeders;

use App\Models\DailyEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DailyEntrySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'email' => 'demo@wellnesslog.test',
        ]);

        DailyEntry::factory()
            ->count(10)
            ->for($user)
            ->sequence(fn(Sequence $index) => [
                'date' => now()
                    ->subDays($index->index)
                    ->format('Y-m-d'),
            ])
            ->create();
    }
}
