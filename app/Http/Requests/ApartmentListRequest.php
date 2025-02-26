<?php

namespace App\Http\Requests;

use App\Models\Apartment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;

class ApartmentListRequest extends FormRequest
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
        $rules = [
            'priceSortOrder' => 'array|object',
            'areaSortOrder' => 'array|object'
        ];

        $searchFields = new Collection();
        $searchFields = $searchFields->push(...Apartment::$searchableFields);
        $searchFields = $searchFields->toArray();

        foreach ($searchFields as $field) {
            $rules[$field] = 'nullable';
        }

        return $rules;
    }
}
