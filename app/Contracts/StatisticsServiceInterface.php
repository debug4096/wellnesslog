<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

interface StatisticsServiceInterface
{
    public function getStatisticsForPeriod(User $user, ?string $dateFrom, ?string $dateTo): array;
}
