<?php

namespace App\Http\Requests\Classes;

class StoreClassRequest extends ClassRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
