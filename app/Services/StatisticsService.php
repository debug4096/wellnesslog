<?php

namespace App\Services;

use App\Contracts\EntryServiceInterface;
use App\Contracts\StatisticsServiceInterface;
use App\Models\User;
use Illuminate\Support\Collection;

class StatisticsService implements StatisticsServiceInterface
{
    public function __construct(
        private readonly EntryServiceInterface $entryService
    ) {
    }

    public function getStatisticsForPeriod(User $user, ?string $dateFrom, ?string $dateTo): array
    {
        $entries = $this->entryService->getEntriesForPeriod($user, $dateFrom, $dateTo);

        return [
            'median_mood_level'    => $this->calcMedianValue($entries, 'mood_level'),
            'median_energy_level'  => $this->calcMedianValue($entries, 'energy_level'),
            'median_sleep_minutes' => $this->calcMedianValue($entries, 'sleep_minutes'),
        ];
    }

    private function calcMedianValue(Collection $entries, string $column): float
    {
        $values = $entries
            ->pluck($column)
            ->filter(fn($v) => $v !== null)
            ->values()
            ->toArray();

        if (empty($values)) {
            return 0;
        }

        sort($values);
        $count = count($values);
        $mid = (int)($count / 2);

        return $count % 2 === 0
            ? ($values[$mid - 1] + $values[$mid]) / 2
            : $values[$mid];
    }
}
