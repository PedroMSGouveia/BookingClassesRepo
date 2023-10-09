<?php

declare(strict_types=1);

namespace App\Http\Requests\Classes;

class GetClassesRequest extends ClassRequest
{
    public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'name' => 'string',
            'startDate' => 'date|date_format:Y-m-d',
            'endDate' =>  [
                'date',
                'date_format:Y-m-d',
                $this->input('startDate') !== null ? 'after_or_equal:startDate' : ''
            ],
        ];
    }
}
