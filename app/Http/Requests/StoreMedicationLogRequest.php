<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicationLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('log', $this->route('medication'));
    }

    public function rules(): array
    {
        return [
            'taken_at' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'dosage' => ['sometimes', 'numeric', 'min:0.001', 'max:999.999'],
            'notes'  => ['sometimes', 'string', 'max:5000'],
        ];
    }
}
