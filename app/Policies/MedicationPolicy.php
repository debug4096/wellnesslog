<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Medication;
use App\Models\User;

class MedicationPolicy
{
    public function view(User $user, Medication $medication): bool
    {
        return $user->id === $medication->user_id;
    }

    public function viewLogs(User $user, Medication $medication): bool
    {
        return $user->id === $medication->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Medication $medication): bool
    {
        return $user->id === $medication->user_id;
    }

    public function log(User $user, Medication $medication): bool
    {
        return $user->id === $medication->user_id;
    }

    public function delete(User $user, Medication $medication): bool
    {
        return $user->id === $medication->user_id;
    }
}
