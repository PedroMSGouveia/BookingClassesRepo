<?php

declare(strict_types=1);

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;

class DeleteClassRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'startDate' => 'required|date',
            'endDate' => [
                'required',
                'date',
                'after_or_equal:startDate'
            ]
        ];
    }
}
