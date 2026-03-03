<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\EntryServiceInterface;
use App\Models\DailyEntry;
use App\Models\User;
use Illuminate\Support\Collection;

class EntryService implements EntryServiceInterface
{
    public function createEntry(User $user, array $data): DailyEntry
    {
        return $user->dailyEntries()->create($data);
    }

    public function updateEntry(DailyEntry $entry, array $data): DailyEntry
    {
        $entry->update($data);

        return $entry->fresh();
    }

    public function deleteEntry(DailyEntry $entry): void
    {
        $entry->delete();
    }

    public function getEntriesForPeriod(User $user, ?string $dateFrom, ?string $dateTo): Collection
    {
        return $user->dailyEntries()
            ->when($dateFrom, fn ($q) => $q->where('date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->where('date', '<=', $dateTo))
            ->orderBy('date', 'desc')
            ->get();
    }
}
