<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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

    /**
     * @return array<string, mixed>
     */
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
