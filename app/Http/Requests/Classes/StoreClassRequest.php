<?php

declare(strict_types=1);

namespace App\Http\Requests\Classes;

class StoreClassRequest extends ClassRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => [
                'required',
                'date',
                'after_or_equal:startDate'
            ],
            'capacity' => 'required|integer',
        ];
    }
}
