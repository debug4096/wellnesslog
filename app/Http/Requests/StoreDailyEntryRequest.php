<?php

namespace App\Http\Requests;

use App\Enums\EnergyLevel;
use App\Enums\MoodLevel;
use App\Models\DailyEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreDailyEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', DailyEntry::class);
    }

    public function rules(): array
    {
        return [
            'date'          => [
                'required',
                'date',
                Rule::unique('daily_entries')
                    ->where('user_id', $this->user()->id),
            ],
            'mood_level'    => ['required', new Enum(MoodLevel::class)],
            'energy_level'  => ['required', new Enum(EnergyLevel::class)],
            'sleep_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'notes'         => ['nullable', 'string', 'max:5000'],
        ];
    }
}
