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
            'date_from' => [
                'sometimes',
                'date',
                $this->has('date_to') ? 'before_or_equal:date_to' : '',
            ],
            'date_to' => [
                'sometimes',
                'date',
                $this->has('date_from') ? 'after_or_equal:date_from' : '',
            ],
        ];
    }
}
