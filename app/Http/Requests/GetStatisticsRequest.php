<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetStatisticsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_from' => ['sometimes', 'date', 'before_or_equal:date_to'],
            'date_to'   => ['sometimes', 'date', 'after_or_equal:date_from'],
        ];
    }
}
