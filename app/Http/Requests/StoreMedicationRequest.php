<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\MedicationFrequency;
use App\Enums\MedicationUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreMedicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'min:3', 'max:100'],
            'dosage'        => ['required', 'numeric', 'min:0.001', 'max:999.999'],
            'unit'          => ['required', new Enum(MedicationUnit::class)],
            'frequency'     => ['required', new Enum(MedicationFrequency::class)],
            'reminder_time' => ['nullable', 'string', 'date_format:H:i'],
        ];
    }
}
