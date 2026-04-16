<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
