<?php

namespace App\Repositories\Queries\RelationshipEntityQuery;

use App\Models\Location;
use Illuminate\Support\Collection;

trait ProcessingOfPlacementDataNoteTrait
{
    public function processingOfPlacementData(string $cityCode): array
    {
        $locationData = Location::select(['district', 'region', 'locality', 'id', 'capital'])->where('code', $cityCode)->get();
        $capitals = $locationData->pluck('capital')->unique();
        $capitalDistricts = $locationData->whereNotIn('locality', $capitals)->pluck('district')->unique();
        //Не включаем район если он районная столица или столичная область
        //NOTE: в capital districts попадает Мошковский Сельсовет
        $districts = $locationData
            ->whereNotIn('district', $capitalDistricts)
            ->pluck('district')
            ->merge($locationData->whereNotIn('locality', $capitals)->pluck('locality'))
            ->unique();
        return [
            'locationData' => $locationData,
            'capitals' => $capitals,
            'capitalDistricts' => $capitalDistricts,
            'districts' => $districts,
        ];
    }
}
