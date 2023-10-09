<?php

declare(strict_types=1);

namespace App\Http\Requests\Classes;

use App\Exceptions\RequestValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'startDate.required' => 'The start date field is required.',
            'startDate.date' => 'The start date must be a valid date.',
            'endDate.required' => 'The end date field is required.',
            'endDate.date' => 'The end date must be a valid date.',
            'endDate.after_or_equal' => 'The end date must be after or equal to the start date.',
            'capacity.required' => 'The capacity field is required.',
            'capacity.integer' => 'The capacity field must be an integer.',
            'capacity.min' => 'The capacity field must be greater than or equal to 1.',
            'page.integer' => 'The page field must be an integer.',
            'page.min' => 'The page field must be greater than or equal to 1.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new RequestValidationException($validator);
    }

}
