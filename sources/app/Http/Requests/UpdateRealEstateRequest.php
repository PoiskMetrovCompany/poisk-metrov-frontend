<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRealEstateRequest extends FormRequest
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
            'id' => 'integer|required',
            'h1' => 'string|required',
            'head_title' => 'string|nullable',
            'description' => 'string|nullable',
            'primary_material' => 'string|nullable',
            'primary_ceiling_height' => 'numeric|nullable',
            'elevator' => 'string|nullable',
            'floors' => 'nullable',
            'corpuses' => 'integer|nullable',
            'parking' => 'string|nullable',
            'meta' => 'string|nullable',
            'on_main_page' => 'boolean|nullable'
        ];
    }
}
