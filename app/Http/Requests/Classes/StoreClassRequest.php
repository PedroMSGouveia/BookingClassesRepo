<?php

declare(strict_types=1);

namespace App\Http\Requests\Classes;

class StoreClassRequest extends ClassRequest
{

    public function rules(): array
    {
        $today = now()->format('Y-m-d');

        return [
            'name' => 'required|string|max:255',
            'startDate' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:'.$today
            ],
            'endDate' => [
                'required',
                'date',
                'after_or_equal:startDate',
                'date_format:Y-m-d'
            ],
            'capacity' => 'required|integer|min:1',
        ];
    }
}
