<?php

namespace App\Services;

use App\Core\Interfaces\Services\ResidentialComplexPriceServiceInterface;
use App\Models\Apartment;

final class ResidentialComplexPriceService implements ResidentialComplexPriceServiceInterface
{
    public function getMinPrice(?int $complexId, ?string $complexKey): ?int
    {
        $minPrice = null;

        if (!is_null($complexId)) {
            $priceById = Apartment::query()->where('complex_id', $complexId)->min('price');
            if (!is_null($priceById)) {
                $minPrice = $priceById;
            }
        }

        if (is_null($minPrice) && !is_null($complexKey)) {
            $priceByKey = Apartment::query()->where('complex_key', $complexKey)->min('price');
            if (!is_null($priceByKey)) {
                $minPrice = $priceByKey;
            }
        }

        return $minPrice;
    }

    public function formatMillions(?int $price): ?string
    {
        if ($price === null) {
            return null;
        }
        return number_format($price / 1000000, 1, ',', '');
    }

    public function augmentPriceFrom(iterable $complexes): array
    {
        $augmented = [];

        foreach ($complexes as $item) {
            $model = $item;
            $asArray = is_object($item) && method_exists($item, 'toArray') ? $item->toArray() : (array) $item;

            $complexId = $asArray['id'] ?? (is_object($model) ? ($model->id ?? null) : null);
            $complexKey = $asArray['key'] ?? (is_object($model) ? ($model->key ?? null) : null);

            $min = $this->getMinPrice($complexId, $complexKey);
            $asArray['price_from'] = $this->formatMillions($min);

            $augmented[] = $asArray;
        }

        return $augmented;
    }
}


