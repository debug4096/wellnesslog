<?php

namespace App\Policies;

use App\Models\DailyEntry;
use App\Models\User;

class DailyEntryPolicy
{
    public function view(User $user, DailyEntry $dailyEntry): bool
    {
        return $user->id === $dailyEntry->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, DailyEntry $dailyEntry): bool
    {
        return $user->id === $dailyEntry->user_id;
    }

    public function delete(User $user, DailyEntry $dailyEntry): bool
    {
        return $user->id === $dailyEntry->user_id;
    }
}
