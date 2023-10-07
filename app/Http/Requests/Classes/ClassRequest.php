<?php

namespace App\Http\Requests\Classes;

use App\Exceptions\CustomValidationException;
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

    /**
     * Get the validation error messages
     *
     * @return string REVER
     */
    public function messages()
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
            'page.integer' => 'The page field must be an integer.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new CustomValidationException($validator);
    }

}
