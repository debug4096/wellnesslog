<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\DailyEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'DailyEntry',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-05-20'),
        new OA\Property(
            property: 'mood_level',
            description: 'Mood on a 1-10 scale: 1 Terrible, 5 Average, 7 Good, 10 Perfect',
            type: 'integer',
            example: 7,
            enum: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        ),
        new OA\Property(
            property: 'energy_level',
            description: 'Energy on a 1-10 scale: 1 Exhausted, 5 Average, 10 Supercharged',
            type: 'integer',
            example: 6,
            enum: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        ),
        new OA\Property(property: 'sleep_minutes', type: 'integer', example: 450, nullable: true),
        new OA\Property(property: 'notes', type: 'string', example: 'Slept well, productive day.', nullable: true),
        new OA\Property(
            property: 'created_at',
            type: 'string',
            format: 'date-time',
            example: '2026-05-20T08:30:00.000000Z',
        ),
        new OA\Property(
            property: 'updated_at',
            type: 'string',
            format: 'date-time',
            example: '2026-05-20T08:30:00.000000Z',
        ),
    ],
    type: 'object',
)]
/**
 * @mixin DailyEntry
 */
class DailyEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'date'          => $this->date->format('Y-m-d'),
            'mood_level'    => $this->mood_level->value,
            'energy_level'  => $this->energy_level->value,
            'sleep_minutes' => $this->sleep_minutes,
            'notes'         => $this->notes,
            'created_at'    => $this->created_at->toISOString(),
            'updated_at'    => $this->updated_at->toISOString(),
        ];
    }
}
