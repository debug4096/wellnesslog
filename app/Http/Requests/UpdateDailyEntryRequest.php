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
        return $this->user()->can('update', $this->route('entry'));
    }

    public function rules(): array
    {
        return [
            'date' => [
                'sometimes',
                'date',
                Rule::unique('daily_entries')
                    ->where('user_id', $this->user()->id)
                    ->ignore($this->route('entry')),
            ],
            'mood_level'    => ['sometimes', new Enum(MoodLevel::class)],
            'energy_level'  => ['sometimes', new Enum(EnergyLevel::class)],
            'sleep_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'notes'         => ['nullable', 'string', 'max:5000'],
        ];
    }
}
