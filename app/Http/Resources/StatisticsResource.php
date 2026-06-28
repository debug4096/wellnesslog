<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Statistics',
    properties: [
        new OA\Property(
            property: 'median_mood_level',
            description: 'Median mood (1-10 scale) over the period. Returns 0 when no entries exist in range.',
            type: 'number',
            format: 'float',
            example: 5.5,
        ),
        new OA\Property(
            property: 'median_energy_level',
            description: 'Median energy (1-10 scale) over the period. Returns 0 when no entries exist in range.',
            type: 'number',
            format: 'float',
            example: 4.5,
        ),
        new OA\Property(
            property: 'median_sleep_minutes',
            description: 'Median sleep duration in minutes over the period. Returns 0 when no entries exist in range.',
            type: 'number',
            format: 'float',
            example: 420,
        ),
        new OA\Property(
            property: 'period',
            properties: [
                new OA\Property(
                    property: 'date_from',
                    type: 'string',
                    format: 'date',
                    example: '2026-05-01',
                    nullable: true,
                ),
                new OA\Property(
                    property: 'date_to',
                    type: 'string',
                    format: 'date',
                    example: '2026-05-31',
                    nullable: true,
                ),
            ],
            type: 'object',
        ),
    ],
    type: 'object',
)]
class StatisticsResource extends JsonResource
{
    /**
     * @param  array{median_mood_level: float, median_energy_level: float, median_sleep_minutes: float}  $resource
     */
    public function __construct(
        array $resource,
        private readonly ?string $dateFrom,
        private readonly ?string $dateTo,
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'median_mood_level'    => $this->resource['median_mood_level'],
            'median_energy_level'  => $this->resource['median_energy_level'],
            'median_sleep_minutes' => $this->resource['median_sleep_minutes'],
            'period'               => [
                'date_from' => $this->dateFrom,
                'date_to'   => $this->dateTo,
            ],
        ];
    }
}
