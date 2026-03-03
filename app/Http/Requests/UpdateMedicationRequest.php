<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\MedicationFrequency;
use App\Enums\MedicationUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateMedicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('medication'));
    }

    public function rules(): array
    {
        return [
            'name'      => ['sometimes', 'string', 'min:3', 'max:100'],
            'dosage'    => ['sometimes', 'numeric', 'min:0.001', 'max:999.999'],
            'unit'      => ['sometimes', new Enum(MedicationUnit::class)],
            'frequency' => ['sometimes', new Enum(MedicationFrequency::class)],
        ];
    }
}
