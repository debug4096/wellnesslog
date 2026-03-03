<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\DailyEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DailyEntry
 */
class DailyEntryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
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
