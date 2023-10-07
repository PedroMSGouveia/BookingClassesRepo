<?php

namespace App\Http\Requests\Bookings;

use Illuminate\Validation\Rule;

class GetBookingsRequest extends BookingsRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
