<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MedicationFrequency;
use App\Enums\MedicationUnit;
use Database\Factories\MedicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    /** @use HasFactory<MedicationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'dosage',
        'unit',
        'frequency',
        'reminder_time',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicationLogs(): HasMany
    {
        return $this->hasMany(MedicationLog::class);
    }

    protected function casts(): array
    {
        return [
            'dosage'        => 'decimal:3',
            'unit'          => MedicationUnit::class,
            'frequency'     => MedicationFrequency::class,
            'reminder_time' => 'datetime:H:i',
        ];
    }
}
