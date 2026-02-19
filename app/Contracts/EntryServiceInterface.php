<?php

namespace App\Contracts;

use App\Models\DailyEntry;
use App\Models\User;
use Illuminate\Support\Collection;

interface EntryServiceInterface
{
    public function createEntry(User $user, array $data): DailyEntry;

    public function updateEntry(DailyEntry $entry, array $data): DailyEntry;

    public function deleteEntry(DailyEntry $entry): void;

    public function getEntriesForPeriod(User $user, ?string $dateFrom, ?string $dateTo): Collection;
}
