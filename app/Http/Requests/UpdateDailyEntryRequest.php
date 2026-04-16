<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\EnergyLevel;
use App\Enums\MoodLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateDailyEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => [
                'sometimes',
                'date',
                'before_or_equal:today',
                Rule::unique('daily_entries')
                    ->where('user_id', $this->user()->id)
                    ->ignore($this->route('entry')->id),
            ],
            'mood_level'    => ['sometimes', new Enum(MoodLevel::class)],
            'energy_level'  => ['sometimes', new Enum(EnergyLevel::class)],
            'sleep_minutes' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:1440'],
            'notes'         => ['sometimes', 'nullable', 'string', 'max:5000'],
        ];
    }
}
