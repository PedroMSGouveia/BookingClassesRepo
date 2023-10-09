<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookings;

use Illuminate\Validation\Rule;

class GetBookingsRequest extends BookingsRequest
{
    public function rules(): array
    {
        return [
            'className' => 'string',
            'personName' => 'string',
            'page' => 'integer|min:1',
            'startDate' => 'date|date_format:Y-m-d',
            'endDate' =>  [
                'date',
                'date_format:Y-m-d',
                $this->input('startDate') !== null ? 'after_or_equal:startDate' : ''
            ],
        ];
    }
}
