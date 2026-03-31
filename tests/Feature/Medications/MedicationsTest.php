<?php

declare(strict_types=1);

namespace Tests\Feature\Medications;

use App\Enums\MedicationFrequency;
use App\Enums\MedicationUnit;
use App\Models\Medication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_medication(): void
    {
        $user = User::factory()->create();

        $data = [
            'name'          => 'Aspirin',
            'dosage'        => 0.125,
            'unit'          => MedicationUnit::Mg->value,
            'frequency'     => MedicationFrequency::OnceDaily->value,
            'reminder_time' => '08:00',
        ];

        $this->actingAs($user)->postJson('/api/medications', $data)
            ->assertStatus(201)
            ->assertJsonPath('data.reminder_time', '08:00');

        $data['user_id'] = $user->id;
        unset($data['reminder_time']);
        $this->assertDatabaseHas('medications', $data);
    }

    public function test_medication_soft_delete(): void
    {
        $user = User::factory()->create();
        $medication = Medication::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->deleteJson("/api/medications/{$medication->id}")
            ->assertStatus(204);

        $this->assertSoftDeleted($medication);
    }

    public function test_user_has_access_to_own_medications(): void
    {
        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();

        $medication = Medication::factory()->create(['user_id' => $userOne->id]);
        Medication::factory()->count(10)->create(['user_id' => $userTwo->id]);

        $this->actingAs($userOne)->getJson('/api/medications')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $medication->id);
    }

    public function test_user_can_update_own_medication(): void
    {
        $user = User::factory()->create();
        $medication = Medication::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->putJson("/api/medications/{$medication->id}", ['name' => 'Test medication'])
            ->assertStatus(200);

        $this->assertDatabaseHas('medications', [
            'id'   => $medication->id,
            'name' => 'Test medication',
        ]);
    }

    public function test_user_cant_access_other_users_medication(): void
    {
        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();

        $medication = Medication::factory()->create(['user_id' => $userTwo->id]);

        $this->actingAs($userOne)
            ->putJson("/api/medications/{$medication->id}", ['name' => 'Test medication'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('medications', [
            'id'   => $medication->id,
            'name' => 'Test medication',
        ]);

        $this->actingAs($userOne)->getJson("/api/medications/{$medication->id}")
            ->assertStatus(403);

        $this->actingAs($userOne)->deleteJson("/api/medications/{$medication->id}")
            ->assertStatus(403);
    }

    public function test_user_cant_create_medication_w_invalid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/medications', [
            'name'          => 'Aspirin',
            'dosage'        => -0.125,
            'unit'          => MedicationUnit::Mg->value,
            'frequency'     => MedicationFrequency::OnceDaily->value,
            'reminder_time' => '08:00',
        ])->assertStatus(422);

        $this->assertDatabaseCount('medications', 0);
    }
}
