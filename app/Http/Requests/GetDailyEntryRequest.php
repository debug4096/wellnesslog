<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetDailyEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'date_from' => ['sometimes', 'date'],
            'date_to'   => ['sometimes', 'date'],
        ];

        if ($this->has('date_to')) {
            $rules['date_from'][] = 'before_or_equal:date_to';
        }

        if ($this->has('date_from')) {
            $rules['date_to'][] = 'after_or_equal:date_from';
        }

        return $rules;
    }
}
