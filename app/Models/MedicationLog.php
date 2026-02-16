<?php

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
        'user_id',
        'medication_id',
        'taken_at',
        'dosage',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
