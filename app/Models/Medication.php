<?php

namespace App\Models;

use App\Enums\MedicationFrequency;
use App\Enums\MedicationUnit;
use Database\Factories\MedicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    /** @use HasFactory<MedicationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'dosage',
        'unit',
        'frequency',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'dosage'    => 'decimal:2',
            'unit'      => MedicationUnit::class,
            'frequency' => MedicationFrequency::class,
        ];
    }
}
