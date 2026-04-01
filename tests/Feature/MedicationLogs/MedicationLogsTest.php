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
        $medication = Medication::factory()->create(['user_id' => $user->id]);

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
        $medicationOne = Medication::factory()->create(['user_id' => $userOne->id]);
        MedicationLog::factory()->count(5)->create(['medication_id' => $medicationOne->id]);

        $userTwo = User::factory()->create();
        $medicationTwo = Medication::factory()->create(['user_id' => $userTwo->id]);
        MedicationLog::factory()->count(3)->create(['medication_id' => $medicationTwo->id]);

        $this->actingAs($userOne)->getJson("/api/medications/{$medicationOne->id}/logs")
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }
}
