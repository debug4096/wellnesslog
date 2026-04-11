<?php

declare(strict_types=1);

namespace Tests\Feature\MedicationLogs;

use App\Models\Medication;
use App\Models\MedicationLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicationLogsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_medication_log(): void
    {
        $user = User::factory()->create();
        $medication = Medication::factory()->for($user)->create();

        $data = [
            'taken_at' => now()->format('Y-m-d H:i:s'),
            'dosage'   => 0.125,
            'notes'    => 'test note',
        ];

        $response = $this->actingAs($user)
            ->postJson("/api/medications/{$medication->id}/logs", $data)
            ->assertStatus(201)
            ->assertExactJsonStructure([
                'data' => [
                    'id',
                    'medication_id',
                    'taken_at',
                    'dosage',
                    'notes',
                    'created_at',
                ],
            ]);

        $data['id'] = $response->json('data.id');
        $data['medication_id'] = $medication->id;
        $this->assertDatabaseHas('medication_logs', $data);
    }

    public function test_user_can_view_own_medication_log(): void
    {
        $userOne = User::factory()->create();
        $medicationOne = Medication::factory()->for($userOne)->create();
        MedicationLog::factory()->count(5)->for($medicationOne)->create();

        $userTwo = User::factory()->create();
        $medicationTwo = Medication::factory()->for($userTwo)->create();
        MedicationLog::factory()->count(3)->for($medicationTwo)->create();

        $this->actingAs($userOne)->getJson("/api/medications/{$medicationOne->id}/logs")
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_user_cant_create_log_for_other_user_medication(): void
    {
        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();
        $medicationTwo = Medication::factory()->for($userTwo)->create();

        $this->actingAs($userOne)->postJson("/api/medications/{$medicationTwo->id}/logs", [
            'taken_at' => now()->format('Y-m-d H:i:s'),
            'dosage'   => 0.125,
            'notes'    => 'test note',
        ])->assertStatus(403);

        $this->assertDatabaseCount('medication_logs', 0);
    }

    public function test_user_cant_view_other_medication_log(): void
    {
        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();

        $medicationTwo = Medication::factory()->for($userTwo)->create();

        $this->actingAs($userOne)->getJson("/api/medications/{$medicationTwo->id}/logs")
            ->assertStatus(403);
    }

    public function test_user_cant_create_medication_log_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $medication = Medication::factory()->for($user)->create();

        $this->actingAs($user)->postJson("/api/medications/{$medication->id}/logs", [
            'taken_at' => now()->format('Y-m-d H:i:s'),
            'dosage'   => -0.125,
            'notes'    => 'test note',
        ])->assertStatus(422);

        $this->assertDatabaseCount('medication_logs', 0);
    }
}
