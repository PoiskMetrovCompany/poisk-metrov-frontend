<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedRequest extends FormRequest
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
            'id' => 'integer',
            // 'name' => 'string|required',
            'url' => 'string|required',
            'format' => 'string|required',
            'city' => 'string|required',
            'fallback_residential_complex_name' => 'string|nullable',
            'default_builder' => 'string|nullable',
        ];
    }
}
