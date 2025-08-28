<?php

namespace App\Core\DTO;

use Illuminate\Support\Collection;

final class CatalogFilterDTO
{
    public ?string $entityType = null;
    public ?int $countRooms = null;
    public ?string $pricing = null;
    public ?int $floors = null;
    public ?string $areaTotal = null;
    public ?string $livingArea = null;
    public ?string $ceilingHeight = null;
    public ?string $layout = null;
    public ?string $finishing = null;
    public ?string $bathroom = null;
    public ?string $apartments = null;
    public ?string $peculiarities = null;
    public ?string $montageType = null;
    public ?string $developer = null;
    public ?string $dueDate = null;
    public ?int $toMetro = null;
    public ?string $elevator = null;
    public ?string $floorCounts = null;
    public ?string $parking = null;
    public ?string $count_rooms = null;
    public ?string $search = null;
    public ?string $city = null;

    /**
     * @return Collection<string, string>
     */
    public function validate(): Collection
    {
        $errors = collect();

        if (!$this->entityType) {
            $errors->put('entity_type', 'Тип сущности обязателен');
        } elseif (!in_array($this->entityType, ['ЖК', 'Квартиры'])) {
            $errors->put('entity_type', 'Тип сущности должен быть "ЖК" или "Квартиры"');
        }

        if ($this->countRooms !== null && $this->countRooms < 1) {
            $errors->put('count_rooms', 'Количество комнат должно быть больше 0');
        }

        if ($this->pricing !== null) {
            if (!$this->isValidPriceFormat($this->pricing)) {
                $errors->put('pricing', 'Неверный формат цены. Используйте число или диапазон "мин-макс"');
            }
        }

        if ($this->floors !== null && $this->floors < 1) {
            $errors->put('floors', 'Этаж должен быть больше 0');
        }

        if ($this->areaTotal !== null) {
            if (!$this->isValidNumericOrRangeFormat($this->areaTotal)) {
                $errors->put('area_total', 'Неверный формат площади. Используйте число или диапазон "мин-макс"');
            }
        }

        if ($this->livingArea !== null) {
            if (!$this->isValidNumericOrRangeFormat($this->livingArea)) {
                $errors->put('living_area', 'Неверный формат жилой площади. Используйте число или диапазон "мин-макс"');
            }
        }

        if ($this->ceilingHeight !== null) {
            if (!$this->isValidNumericOrRangeFormat($this->ceilingHeight)) {
                $errors->put('ceiling_height', 'Неверный формат высоты потолков. Используйте число или диапазон "мин-макс"');
            }
        }

        if ($this->toMetro !== null && $this->toMetro < 0) {
            $errors->put('to_metro', 'Расстояние до метро не может быть отрицательным');
        }

        // Город обязателен
        if (!$this->city) {
            $errors->put('city', 'Код города обязателен');
        }

        return $errors;
    }

    /**
     * @param mixed $price
     * @return bool
     */
    private function isValidPriceFormat($price): bool
    {
        if (is_numeric($price)) {
            return true;
        }

        if (is_string($price) && strpos($price, '-') !== false) {
            [$min, $max] = explode('-', $price, 2);
            return is_numeric($min) && is_numeric($max) && (float)$min <= (float)$max;
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function isValidNumericOrRangeFormat($value): bool
    {
        if (is_numeric($value)) {
            return (float)$value > 0;
        }

        if (is_string($value) && strpos($value, '-') !== false) {
            [$min, $max] = explode('-', $value, 2);
            return is_numeric($min) && is_numeric($max) &&
                   (float)$min > 0 && (float)$max > 0 &&
                   (float)$min <= (float)$max;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->validate()->isNotEmpty();
    }

    /**
     * @return Collection<string, string>
     */
    public function getErrors(): Collection
    {
        return $this->validate();
    }
}
