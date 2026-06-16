<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Medication',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Aspirin'),
        new OA\Property(
            property: 'dosage',
            description: 'Decimal dosage with 3-digit precision, serialized as string to preserve accuracy',
            type: 'string',
            example: '500.000',
        ),
        new OA\Property(
            property: 'unit',
            description: 'Dosage unit: tablet, drop, capsule, ml (milliliter), mg (milligram), injection, spray, patch',
            type: 'string',
            example: 'mg',
            enum: ['tablet', 'drop', 'capsule', 'ml', 'mg', 'injection', 'spray', 'patch'],
        ),
        new OA\Property(
            property: 'frequency',
            description: 'Intake schedule: once_daily, twice_daily, three_times_daily, every_other_day, weekly, as_needed',
            type: 'string',
            example: 'twice_daily',
            enum: ['once_daily', 'twice_daily', 'three_times_daily', 'every_other_day', 'weekly', 'as_needed'],
        ),
        new OA\Property(
            property: 'reminder_time',
            description: 'Daily reminder time in HH:MM format',
            type: 'string',
            pattern: '^([01][0-9]|2[0-3]):[0-5][0-9]$',
            example: '09:30',
            nullable: true,
        ),
        new OA\Property(property: 'is_archived', type: 'boolean', example: false),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-05-20T08:30:00.000000Z'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-05-20T08:30:00.000000Z'),
    ],
    type: 'object',
)]
/**
 * @mixin Medication
 */
class MedicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'dosage'        => $this->dosage,
            'unit'          => $this->unit->value,
            'frequency'     => $this->frequency->value,
            'reminder_time' => $this->reminder_time?->format('H:i'),
            'is_archived'   => $this->trashed(),
            'created_at'    => $this->created_at->toISOString(),
            'updated_at'    => $this->updated_at->toISOString(),
        ];
    }
}
