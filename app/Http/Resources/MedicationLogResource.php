<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\MedicationLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MedicationLog
 */
class MedicationLogResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'medication_id' => $this->medication_id,
            'taken_at'      => $this->taken_at->format('Y-m-d'),
            'dosage'        => $this->dosage,
            'notes'         => $this->notes,
            'created_at'    => $this->created_at->toISOString(),
        ];
    }
}
