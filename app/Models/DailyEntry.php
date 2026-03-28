<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EnergyLevel;
use App\Enums\MoodLevel;
use Database\Factories\DailyEntryFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyEntry extends Model
{
    /** @use HasFactory<DailyEntryFactory> */
    use HasFactory;

    protected $fillable = [
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
            'date'         => 'date:Y-m-d',
            'mood_level'   => MoodLevel::class,
            'energy_level' => EnergyLevel::class,
        ];
    }

    #[Scope]
    public function dateFrom(Builder $query, string $date): Builder
    {
        return $query->where('date', '>=', $date);
    }

    #[Scope]
    public function dateTo(Builder $query, string $date): Builder
    {
        return $query->where('date', '<=', $date);
    }
}
