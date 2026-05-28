<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\MedicationLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MedicationLog',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'medication_id', type: 'integer', example: 1),
        new OA\Property(property: 'taken_at', type: 'string', format: 'date-time', example: '2026-05-28T09:00:00.000000Z'),
        new OA\Property(
            property: 'dosage',
            description: 'Decimal dosage with 3-digit precision, serialized as string to preserve accuracy',
            type: 'string',
            example: '0.125',
            nullable: true,
        ),
        new OA\Property(property: 'notes', type: 'string', example: 'Taken with breakfast.', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-05-28T09:00:00.000000Z'),
    ],
    type: 'object',
)]
/**
 * @mixin MedicationLog
 */
class MedicationLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'medication_id' => $this->medication_id,
            'taken_at'      => $this->taken_at->toISOString(),
            'dosage'        => $this->dosage,
            'notes'         => $this->notes,
            'created_at'    => $this->created_at->toISOString(),
        ];
    }
}
