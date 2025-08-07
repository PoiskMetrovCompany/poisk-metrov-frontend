<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilteredMortgagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categories' => 'array',
            'banks' => 'array',
            'preferred_price' => 'numeric|min:0',
            'preferred_year' => 'numeric|min:1',
            'preferred_initial_fee' => 'numeric|min:0',
            'sorting_parameter' => 'required|string',
            'sorting_direction' => 'required|string'
        ];
    }
}
