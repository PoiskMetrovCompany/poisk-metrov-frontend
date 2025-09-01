<?php

namespace App\Http\Resources\Apartments;

use App\Core\Abstracts\AbstractResource;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentResource extends AbstractResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $base = parent::toArray($request);

        // Определяем корректное значение searchData для includes
        $searchData = $this->id;
        $includesParam = $request->get('includes');
        if (!empty($includesParam)) {
            $first = trim(explode(',', $includesParam)[0]);
            if ($first !== '' && isset(Apartment::RELATIONSHIP[$first]['main_table_value'])) {
                $mainField = Apartment::RELATIONSHIP[$first]['main_table_value'];
                if (isset($this->{$mainField})) {
                    $searchData = $this->{$mainField};
                }
            }
        }

        return array_merge(
            $base,
            self::relationshipListOperation(Apartment::class, $searchData, $request->all(), Apartment::RELATIONSHIP)
        );
    }
}
