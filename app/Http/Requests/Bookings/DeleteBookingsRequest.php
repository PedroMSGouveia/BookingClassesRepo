<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookings;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBookingsRequest extends BookingsRequest
{
    public function rules(): array
    {
        return [
            'personName' => 'required|string|max:255',
            'date' => 'required|date|date_format:Y-m-d'
        ];
    }
}
