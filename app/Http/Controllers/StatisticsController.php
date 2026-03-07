<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\StatisticsServiceInterface;
use App\Http\Requests\GetStatisticsRequest;
use App\Http\Resources\StatisticsResource;

class StatisticsController extends Controller
{
    public function __construct(
        private readonly StatisticsServiceInterface $statisticsService,
    ) {}

    public function index(GetStatisticsRequest $request): StatisticsResource
    {
        $validated = $request->validated();

        $statistics = $this->statisticsService->getStatisticsForPeriod(
            $request->user(),
            $validated['date_from'] ?? null,
            $validated['date_to'] ?? null,
        );

        return new StatisticsResource(
            $statistics,
            $validated['date_from'] ?? null,
            $validated['date_to'] ?? null,
        );
    }
}
