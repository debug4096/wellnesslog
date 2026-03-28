<?php

declare(strict_types=1);

namespace Tests\Feature\DailyEntries;

use App\Enums\EnergyLevel;
use App\Enums\MoodLevel;
use App\Models\DailyEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyEntriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_daily_entry(): void
    {
        $user = User::factory()->create();

        $data = [
            'date'          => now()->format('Y-m-d'),
            'mood_level'    => MoodLevel::Good->value,
            'energy_level'  => EnergyLevel::Energetic->value,
            'sleep_minutes' => 60 * 8,
            'notes'         => 'test note',
        ];

        $this->actingAs($user)->postJson('/api/entries', $data)
            ->assertStatus(201)
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'date',
                    'mood_level',
                    'energy_level',
                    'sleep_minutes',
                    'notes',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('daily_entries', $data);
    }

    public function test_user_can_only_see_their_own_entries(): void
    {
        $user = User::factory()->create();

        $dailyEntry = DailyEntry::factory()->create(['user_id' => $user->id]);
        DailyEntry::factory()->count(10)->create();

        $this->actingAs($user)->getJson('/api/entries')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $dailyEntry->id);
    }

    public function test_user_can_update_their_own_entry(): void
    {
        $user = User::factory()->create();
        $dailyEntry = DailyEntry::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->putJson('/api/entries/' . $dailyEntry->id, [
            'notes' => 'updated note',
        ])->assertStatus(200)
            ->assertJsonPath('data.notes', 'updated note');
    }

    public function test_user_cannot_update_another_users_entry(): void
    {
        $user = User::factory()->create();
        $dailyEntry = DailyEntry::factory()->create();

        $this->actingAs($user)->putJson('/api/entries/' . $dailyEntry->id, ['notes' => 'updated note'])
            ->assertStatus(403);
    }

    public function test_entries_can_be_filtered_by_date_range(): void
    {
        $user = User::factory()->create();

        DailyEntry::factory()->create([
            'user_id' => $user->id,
            'date'    => now()->format('Y-m-d'),
        ]);

        DailyEntry::factory()->count(10)->sequence(
            fn (Sequence $sequence) => ['date' => now()->subDays($sequence->index + 1)->format('Y-m-d')],
        )->create(['user_id' => $user->id]);

        $this->actingAs($user)->getJson(
            '/api/entries?' . http_build_query([
                'date_from' => now()->format('Y-m-d'),
                'date_to'   => now()->format('Y-m-d'),
            ]),
        )->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.date', now()->format('Y-m-d'));
    }
}
