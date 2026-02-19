<?php

namespace App\Services;

use App\Models\Medication;
use App\Models\MedicationLog;
use App\Models\User;
use Illuminate\Support\Collection;

class MedicationService
{
    public function getUserMedications(User $user): Collection
    {
        return $user->medications()->get();
    }

    public function createMedication(User $user, array $data): Medication
    {
        return $user->medications()->create($data);
    }

    public function updateMedication(Medication $medication, array $data): Medication
    {
        $medication->update($data);

        return $medication->fresh();
    }

    public function deleteMedication(Medication $medication): void
    {
        $medication->delete();
    }

    public function getMedicationLog(Medication $medication): Collection
    {
        return $medication->medicationLogs()->get();
    }

    public function createMedicationLog(Medication $medication, array $data): MedicationLog
    {
        return $medication->medicationLogs()->create($data);
    }
}
