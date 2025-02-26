<?php

namespace App\Http\Requests;

use App\Models\Apartment;
use App\Models\Location;
use App\Models\ResidentialComplex;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;

class GetFilteredCatalogueRequest extends FormRequest
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
            'offset' => 'int',
            'limit' => 'int',
            'cardelement' => 'string',
        ];

        $searchFields = new Collection();
        $searchFields = $searchFields->push(...Apartment::$searchableFields);
        $searchFields = $searchFields->push(...ResidentialComplex::$searchableFields);
        $searchFields = $searchFields->push(...Location::$searchableFields);
        $searchFields = $searchFields->toArray();

        foreach ($searchFields as $field) {
            $rules[$field] = 'nullable';
        }

        return $rules;
    }
}
