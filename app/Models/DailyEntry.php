<?php

namespace App\Models;

use App\Enums\EnergyLevel;
use App\Enums\MoodLevel;
use Database\Factories\DailyEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyEntry extends Model
{
    /** @use HasFactory<DailyEntryFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'mood_level',
        'energy_level',
        'sleep_minutes',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'date'         => 'date',
            'mood_level'   => MoodLevel::class,
            'energy_level' => EnergyLevel::class,
        ];
    }
}
