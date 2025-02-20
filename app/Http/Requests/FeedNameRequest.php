<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedNameRequest extends FormRequest
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
            'feed_name' => 'string|required',
            'site_name' => 'string|nullable',
            'create_new' => 'bool',
        ];
    }
}
