<?php

namespace App\Services;

use App\Core\Interfaces\Services\CatalogueStatisticServiceInterface;
use App\Models\Apartment;
use App\Models\ResidentialComplex;
use Illuminate\Support\Collection;


class CatalogueStatisticService implements CatalogueStatisticServiceInterface
{

    public function getCatalogueStatistics(?string $cityCode = null): Collection
    {
        return collect([
            $this->createStatisticItem('ЖК', $cityCode),
            $this->createStatisticItem('Квартиры', $cityCode),
            $this->createStatisticItem('Апартаменты', $cityCode),
        ]);
    }


    private function createStatisticItem(string $type, ?string $cityCode): array
    {
        return [
            'type' => $type,
            'meta' => $this->getMetaCounts($type, $cityCode),
            'attributes' => $this->getAttributes($type, $cityCode),
        ];
    }


    private function getMetaCounts(string $type, ?string $cityCode): array
    {
        if ($type === 'ЖК') {
            return [
                ["title" => "Все проекты", "count" => $this->getResidentialComplexCount($cityCode)],
                ["title" => "Популярные", "count" => 0], // TODO: реализовать логику популярных ЖК
                ["title" => "Акции", "count" => 0], // TODO: реализовать логику акций
            ];
        }

        if ($type === 'Квартиры') {
            return [
                ["title" => "Все квартиры", "count" => $this->getApartmentCount($cityCode)],
                ["title" => "Свободные", "count" => 0], // TODO: реализовать логику свободных квартир
                ["title" => "В продаже", "count" => 0], // TODO: реализовать логику квартир в продаже
            ];
        }

        if ($type === 'Апартаменты') {
            return [
                ["title" => "Все апартаменты", "count" => $this->getApartmentsCount($cityCode)],
                ["title" => "Свободные", "count" => 0], // TODO: реализовать логику свободных апартаментов
                ["title" => "В продаже", "count" => 0], // TODO: реализовать логику апартаментов в продаже
            ];
        }

        return [];
    }


    public function getResidentialComplexCount(?string $cityCode): int
    {
        if ($cityCode) {
            return ResidentialComplex::whereHas('location', function ($query) use ($cityCode) {
                $query->where('code', $cityCode);
            })->count();
        }

        return ResidentialComplex::count();
    }


    public function getApartmentCount(?string $cityCode): int
    {
        if ($cityCode) {
            return \DB::table('apartments')
                ->join('residential_complexes', 'apartments.complex_key', '=', 'residential_complexes.key')
                ->join('locations', 'residential_complexes.location_key', '=', 'locations.key')
                ->where('locations.code', $cityCode)
                ->count();
        }

        return Apartment::count();
    }


    public function getApartmentsCount(?string $cityCode): int
    {
        $query = \DB::table('apartments')
            ->where(function ($q) {
                $q->where('apartment_type', 'LIKE', '%апартамент%')
                  ->orWhere('apartment_type', 'LIKE', '%apartment%')
                  ->orWhere('apartment_type', 'LIKE', '%apartments%');
            });

        if ($cityCode) {
            $query->join('residential_complexes', 'apartments.complex_key', '=', 'residential_complexes.key')
                  ->join('locations', 'residential_complexes.location_key', '=', 'locations.key')
                  ->where('locations.code', $cityCode);
        }

        return $query->count();
    }

    /**
     * Получить attributes для статистики
     */
    private function getAttributes(string $type, ?string $cityCode): array
    {
        if ($type === 'ЖК') {
            return $this->getResidentialComplexAttributes($cityCode);
        }

        if ($type === 'Квартиры') {
            return $this->getApartmentAttributes($cityCode);
        }

        if ($type === 'Апартаменты') {
            return $this->getApartmentsAttributes($cityCode);
        }

        return [];
    }

    /**
     * Получить attributes для ЖК
     */
    private function getResidentialComplexAttributes(?string $cityCode): array
    {
        $baseQuery = \App\Models\ResidentialComplex::query();

        if ($cityCode) {
            $baseQuery->whereHas('location', function ($query) use ($cityCode) {
                $query->where('code', $cityCode);
            });
        }

        return [
            [
                "title" => "ЖК у воды",
                "count_prepositions" => $this->countResidentialComplexesByKeywords($baseQuery, ['река', 'озеро', 'водоем', 'пруд', 'речка']),
                "icon" => "/icon/location.svg"
            ],
            [
                "title" => "ЖК в центре",
                "count_prepositions" => $this->countResidentialComplexesByKeywords($baseQuery, ['центр', 'центральный', 'центральная']),
                "icon" => "/icon/light.svg"
            ],
            [
                "title" => "ЖК с видом",
                "count_prepositions" => $this->countResidentialComplexesByKeywords($baseQuery, ['вид', 'панорама', 'панорамный', 'окна']),
                "icon" => "/icon/light.svg"
            ],
            [
                "title" => "ЖК бизнес-класса",
                "count_prepositions" => $this->countResidentialComplexesByKeywords($baseQuery, ['бизнес', 'премиум', 'элитный', 'люкс']),
                "icon" => "/icon/building2.svg"
            ],
            [
                "title" => "ЖК с отделкой",
                "count_prepositions" => $this->countResidentialComplexesByKeywords($baseQuery, ['отделка', 'ремонт', 'чистовая', 'предчистовая']),
                "icon" => "/icon/send.svg"
            ],
            [
                "title" => "ЖК с парками",
                "count_prepositions" => $this->countResidentialComplexesByKeywords($baseQuery, ['парк', 'сквер', 'парковая', 'зона отдыха']),
                "icon" => "/icon/leaf.svg"
            ],
        ];
    }

    /**
     * Получить attributes для апартаментов
     */
    private function getApartmentsAttributes(?string $cityCode): array
    {
        $baseQuery = Apartment::query();
        $baseQuery->where(function ($q) {
            $q->where('apartment_type', 'LIKE', '%апартамент%')
              ->orWhere('apartment_type', 'LIKE', '%apartment%')
              ->orWhere('apartment_type', 'LIKE', '%apartments%');
        });

        if ($cityCode) {
            $baseQuery->whereHas('residentialComplexByKey.location', function ($query) use ($cityCode) {
                $query->where('locations.code', $cityCode);
            });
        }

        return [
            [
                "title" => "Апартаменты у воды",
                "count_prepositions" => $this->countApartmentsByKeywords($baseQuery, ['река', 'озеро', 'водоем', 'пруд', 'речка']),
                "icon" => "/icon/location.svg"
            ],
            [
                "title" => "Апартаменты в центре",
                "count_prepositions" => $this->countApartmentsByKeywords($baseQuery, ['центр', 'центральный', 'центральная']),
                "icon" => "/icon/light.svg"
            ],
            [
                "title" => "Апартаменты с видом",
                "count_prepositions" => $this->countApartmentsByKeywords($baseQuery, ['вид', 'панорама', 'панорамный', 'окна']),
                "icon" => "/icon/light.svg"
            ],
            [
                "title" => "Апартаменты с террасой",
                "count_prepositions" => $this->countApartmentsByKeywords($baseQuery, ['терраса', 'балкон', 'лоджия', 'веранда']),
                "icon" => "/icon/umbrella.svg"
            ],
            [
                "title" => "Апартаменты с отделкой",
                "count_prepositions" => $this->countApartmentsByRenovation($baseQuery),
                "icon" => "/icon/send.svg"
            ],
            [
                "title" => "Апартаменты премиум",
                "count_prepositions" => $this->countApartmentsByKeywords($baseQuery, ['премиум', 'элитный', 'люкс', 'vip']),
                "icon" => "/icon/building2.svg"
            ],
        ];
    }

    private function getApartmentAttributes(?string $cityCode): array
    {
        $baseQuery = Apartment::query();

        if ($cityCode) {
            $baseQuery->whereHas('residentialComplexByKey.location', function ($query) use ($cityCode) {
                $query->where('locations.code', $cityCode);
            });
        }

        return [
            [
                "title" => "1-комнатные",
                "count_prepositions" => $this->countApartmentsByRoomCount($baseQuery, 1),
                "icon" => "/icon/apartment1.svg"
            ],
            [
                "title" => "2-комнатные",
                "count_prepositions" => $this->countApartmentsByRoomCount($baseQuery, 2),
                "icon" => "/icon/apartment2.svg"
            ],
            [
                "title" => "3-комнатные",
                "count_prepositions" => $this->countApartmentsByRoomCount($baseQuery, 3),
                "icon" => "/icon/apartment3.svg"
            ],
            [
                "title" => "Квартиры с террасой",
                "count_prepositions" => $this->countApartmentsByKeywords($baseQuery, ['терраса', 'балкон', 'лоджия', 'веранда']),
                "icon" => "/icon/umbrella.svg"
            ],
            [
                "title" => "Квартиры с отделкой",
                "count_prepositions" => $this->countApartmentsByRenovation($baseQuery),
                "icon" => "/icon/send.svg"
            ],
            [
                "title" => "Пентхаусы",
                "count_prepositions" => $this->countApartmentsByKeywords($baseQuery, ['пентхаус', 'penthouse', 'верхний', 'последний']),
                "icon" => "/icon/home.svg"
            ],
        ];
    }


    private function countApartmentsByRoomCount($baseQuery, int $roomCount): int
    {
        try {
            $query = clone $baseQuery;
            return $query->where('room_count', $roomCount)->count();
        } catch (\Exception $e) {
            \Log::warning('Error counting apartments by room count: ' . $e->getMessage());
            return 0;
        }
    }

    private function countApartmentsByKeywords($baseQuery, array $keywords): int
    {
        try {
            $query = clone $baseQuery;

            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('apartment_type', 'LIKE', "%{$keyword}%")
                      ->orWhere('balcony', 'LIKE', "%{$keyword}%")
                      ->orWhere('head_title', 'LIKE', "%{$keyword}%")
                      ->orWhere('h1', 'LIKE', "%{$keyword}%");
                }
            });

            return $query->count();
        } catch (\Exception $e) {
            \Log::warning('Error counting apartments by keywords: ' . $e->getMessage());
            return 0;
        }
    }

    private function countApartmentsByRenovation($baseQuery): int
    {
        try {
            $query = clone $baseQuery;
            $query->whereNotNull('renovation')
                  ->where('renovation', '!=', '')
                  ->where('renovation', 'NOT LIKE', '%без%')
                  ->where('renovation', 'NOT LIKE', '%нет%');

            return $query->count();
        } catch (\Exception $e) {
            \Log::warning('Error counting apartments by renovation: ' . $e->getMessage());
            return 0;
        }
    }

    private function countResidentialComplexesByKeywords($baseQuery, array $keywords): int
    {
        $query = clone $baseQuery;

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('infrastructure', 'LIKE', "%{$keyword}%")
                  ->orWhere('address', 'LIKE', "%{$keyword}%");
            }
        });

        return $query->count();
    }

}
