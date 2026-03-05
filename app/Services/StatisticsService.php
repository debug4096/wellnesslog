<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\StatisticsServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatisticsService implements StatisticsServiceInterface
{
    /**
     * @return array{median_mood_level: float, median_energy_level: float, median_sleep_minutes: float}
     */
    public function getStatisticsForPeriod(User $user, ?string $dateFrom, ?string $dateTo): array
    {
        $query = $user->dailyEntries()
            ->when($dateFrom, fn ($query, $date) => $query->where('entry_date', '>=', $date))
            ->when($dateTo, fn ($query, $date) => $query->where('entry_date', '<=', $date));

        return [
            'median_mood_level'    => $this->calcMedianValue(clone $query, 'mood_level'),
            'median_energy_level'  => $this->calcMedianValue(clone $query, 'energy_level'),
            'median_sleep_minutes' => $this->calcMedianValue(clone $query, 'sleep_minutes'),
        ];
    }

    private function calcMedianValue(HasMany $query, string $column): float
    {
        $values = $query
            ->whereNotNull($column)
            ->orderBy($column)
            ->pluck($column);

        if ($values->isEmpty()) {
            return 0.0;
        }

        $count = $values->count();
        $mid = (int) ($count / 2);

        return (float) ($count % 2 === 0
            ? ($values[$mid - 1] + $values[$mid]) / 2
            : $values[$mid]);
    }
}
