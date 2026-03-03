<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MedicationLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationLog extends Model
{
    /** @use HasFactory<MedicationLogFactory> */
    use HasFactory;

    protected $fillable = [
        'taken_at',
        'dosage',
        'notes',
    ];

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    protected function casts(): array
    {
        return [
            'taken_at' => 'datetime',
            'dosage'   => 'decimal:3',
        ];
    }
}
