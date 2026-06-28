<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\StatisticsServiceInterface;
use App\Http\Requests\GetStatisticsRequest;
use App\Http\Resources\StatisticsResource;
use OpenApi\Attributes as OA;

class StatisticsController extends Controller
{
    public function __construct(
        private readonly StatisticsServiceInterface $statisticsService,
    ) {}

    #[OA\Get(
        path: '/api/statistics',
        description: 'Returns median mood, energy, and sleep values for the authenticated user. Without date parameters, aggregates over all entries. Medians return 0 when no entries exist in the range.',
        summary: 'Get aggregated wellness statistics over a period',
        security: [['sanctum' => []]],
        tags: ['Statistics'],
        parameters: [
            new OA\Parameter(
                name: 'date_from',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2026-05-01'),
            ),
            new OA\Parameter(
                name: 'date_to',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date', example: '2026-05-31'),
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Aggregated statistics for the period',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/Statistics'),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ],
    )]
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
