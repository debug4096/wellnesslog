<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Medication;
use App\Models\MedicationLog;
use App\Models\User;
use Illuminate\Support\Collection;

interface MedicationServiceInterface
{
    public function getUserMedications(User $user): Collection;

    public function createMedication(User $user, array $data): Medication;

    public function updateMedication(Medication $medication, array $data): Medication;

    public function deleteMedication(Medication $medication): void;

    public function getMedicationLog(Medication $medication): Collection;

    public function createMedicationLog(Medication $medication, array $data): MedicationLog;
}
