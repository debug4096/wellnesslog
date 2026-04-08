<?php

declare(strict_types=1);

namespace Tests\Feature\Statistics;

use App\Enums\EnergyLevel;
use App\Enums\MoodLevel;
use App\Models\DailyEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_statistics_data(): void
    {
        $user = User::factory()->create();

        DailyEntry::factory()->create([
            'user_id'       => $user->id,
            'date'          => now()->subDays(1)->format('Y-m-d'),
            'mood_level'    => MoodLevel::Average->value,
            'energy_level'  => EnergyLevel::AboveAverage->value,
            'sleep_minutes' => 360,
        ]);

        DailyEntry::factory()->create([
            'user_id'       => $user->id,
            'date'          => now()->subDays(2)->format('Y-m-d'),
            'mood_level'    => MoodLevel::AboveAverage->value,
            'energy_level'  => EnergyLevel::Low->value,
            'sleep_minutes' => 480,
        ]);

        $this->actingAs($user)->getJson('api/statistics')
            ->assertStatus(200)
            ->assertJsonPath('data.median_mood_level', 5.5)
            ->assertJsonPath('data.median_energy_level', 4.5)
            ->assertJsonPath('data.median_sleep_minutes', 420);
    }

    public function test_user_can_get_statistics_data_for_period(): void
    {
        $user = User::factory()->create();

        DailyEntry::factory()->create([
            'user_id'       => $user->id,
            'date'          => now()->subDays(1)->format('Y-m-d'),
            'mood_level'    => MoodLevel::Average->value,
            'energy_level'  => EnergyLevel::AboveAverage->value,
            'sleep_minutes' => 420,
        ]);

        DailyEntry::factory()->create([
            'user_id'       => $user->id,
            'date'          => now()->subDays(30)->format('Y-m-d'),
            'mood_level'    => MoodLevel::Excellent->value,
            'energy_level'  => EnergyLevel::VeryHigh->value,
            'sleep_minutes' => 360,
        ]);

        $this->actingAs($user)->getJson(
            'api/statistics?' . http_build_query([
                'date_from' => now()->subDays(5)->format('Y-m-d'),
                'date_to'   => now()->format('Y-m-d'),
            ]),
        )->assertStatus(200)
            ->assertJsonPath('data.median_mood_level', 5)
            ->assertJsonPath('data.median_energy_level', 6)
            ->assertJsonPath('data.median_sleep_minutes', 420);
    }
}
