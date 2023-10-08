<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookings;

use Illuminate\Validation\Rule;

class GetBookingsRequest extends BookingsRequest
{
    public function rules(): array
    {
        return [
            'page' => 'integer',
            'startDate' => 'date',
            'endDate' =>  [
                'date',
                $this->input('startDate') !== null ? 'after_or_equal:startDate' : ''
            ],
        ];
    }
}
