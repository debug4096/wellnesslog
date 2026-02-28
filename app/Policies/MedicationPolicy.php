<?php

namespace App\Policies;

use App\Models\Medication;
use App\Models\User;

class MedicationPolicy
{
    public function view(User $user, Medication $medication): bool
    {
        return $user->id === $medication->user_id;
    }

    public function update(User $user, Medication $medication): bool
    {
        return $user->id === $medication->user_id;
    }

    public function delete(User $user, Medication $medication): bool
    {
        return $user->id === $medication->user_id;
    }
}
