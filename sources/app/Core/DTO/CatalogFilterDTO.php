<?php

namespace App\Core\DTO;

use Illuminate\Support\Collection;

final class CatalogFilterDTO
{
    public ?string $entityType = null;
    public mixed $countRooms = null;
    public ?string $pricing = null;
    public mixed $floors = null;
    public mixed $areaTotal = null;
    public mixed $livingArea = null;
    public ?string $ceilingHeight = null;
    public ?string $layout = null;
    public mixed $finishing = null;
    public mixed $builtYear = null;
    public ?string $bathroom = null;
    public ?string $apartments = null;
    public ?string $peculiarities = null;
    public ?string $montageType = null;
    public ?string $developer = null;
    public ?string $dueDate = null;
    public mixed $toMetro = null;
    public ?string $elevator = null;
    public ?string $floorCounts = null;
    public ?string $parking = null;
    public ?string $search = null;

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

        if ($this->countRooms !== null) {
            if (!$this->isValidRoomCountFormat($this->countRooms)) {
                $errors->put('count_rooms', 'Неверный формат количества комнат. Используйте число (2), массив ([1,2,3]), диапазон (5+) или значения через запятую (1,3,5+,2)');
            }
        }

        if ($this->pricing !== null) {
            if (!$this->isValidPriceFormat($this->pricing)) {
                $errors->put('pricing', 'Неверный формат цены. Используйте число или диапазон "мин-макс"');
            }
        }

        if ($this->floors !== null) {
            if (!$this->isValidFloorFormat($this->floors)) {
                $errors->put('floors', 'Неверный формат этажа. Используйте число (2), массив ([1,2,3]), диапазон (5+) или значения через запятую (1,3,5+,2)');
            }
        }

        if ($this->areaTotal !== null) {
            if (!$this->isValidAreaFormat($this->areaTotal)) {
                $errors->put('area_total', 'Неверный формат общей площади. Используйте число (50), массив ([30,50,70]), диапазон (50-70), диапазон от (50+) или значения через запятую (30,50-70,80+)');
            }
        }

        if ($this->livingArea !== null) {
            if (!$this->isValidAreaFormat($this->livingArea)) {
                $errors->put('living_area', 'Неверный формат жилой площади. Используйте число (30), массив ([20,30,40]), диапазон (20-40), диапазон от (30+) или значения через запятую (20,30-40,50+)');
            }
        }

        if ($this->finishing !== null) {
            if (!$this->isValidFinishingFormat($this->finishing)) {
                $errors->put('finishing', 'Неверный формат отделки. Используйте строку ("Чистовая"), массив (["Чистовая","Черновая"]) или значения через запятую ("Чистовая,Черновая")');
            }
        }

        if ($this->builtYear !== null) {
            if (!$this->isValidBuiltYearFormat($this->builtYear)) {
                $errors->put('built_year', 'Неверный формат года сдачи. Используйте год (2024), статус ("Сдан", "Позднее"), массив ([2024, "Сдан"]) или значения через запятую ("2024,Сдан,Позднее")');
            }
        }

        if ($this->ceilingHeight !== null) {
            if (!$this->isValidNumericOrRangeFormat($this->ceilingHeight)) {
                $errors->put('ceiling_height', 'Неверный формат высоты потолков. Используйте число или диапазон "мин-макс"');
            }
        }

        if ($this->toMetro !== null) {
            if (!$this->isValidMetroTimeFormat($this->toMetro)) {
                $errors->put('to_metro', 'Неверный формат времени до метро. Используйте число (15), массив ([5,10,15]), диапазон (5-15), диапазон от (10+) или значения через запятую (5,10-15,20+)');
            }
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
     * @param mixed $roomCount
     * @return bool
     */
    private function isValidRoomCountFormat($roomCount): bool
    {
        if (is_numeric($roomCount)) {
            return (int)$roomCount > 0;
        }

        if (is_array($roomCount)) {
            foreach ($roomCount as $value) {
                if (!is_numeric($value) || (int)$value < 1) {
                    return false;
                }
            }
            return true;
        }

        if (is_string($roomCount) && str_contains($roomCount, ',')) {
            $values = array_map('trim', explode(',', $roomCount));
            foreach ($values as $value) {
                if (str_ends_with($value, '+')) {
                    $number = rtrim($value, '+');
                    if (!is_numeric($number) || (int)$number < 1) {
                        return false;
                    }
                } else {
                    if (!is_numeric($value) || (int)$value < 1) {
                        return false;
                    }
                }
            }
            return true;
        }

        if (is_string($roomCount) && str_ends_with($roomCount, '+')) {
            $number = rtrim($roomCount, '+');
            return is_numeric($number) && (int)$number > 0;
        }

        return false;
    }

    /**
     * @param mixed $floor
     * @return bool
     */
    private function isValidFloorFormat($floor): bool
    {
        if (is_numeric($floor)) {
            return (int)$floor > 0;
        }

        if (is_array($floor)) {
            foreach ($floor as $value) {
                if (!is_numeric($value) || (int)$value < 1) {
                    return false;
                }
            }
            return true;
        }

        if (is_string($floor) && str_contains($floor, ',')) {
            $values = array_map('trim', explode(',', $floor));
            foreach ($values as $value) {
                if (str_ends_with($value, '+')) {
                    $number = rtrim($value, '+');
                    if (!is_numeric($number) || (int)$number < 1) {
                        return false;
                    }
                } else {
                    if (!is_numeric($value) || (int)$value < 1) {
                        return false;
                    }
                }
            }
            return true;
        }

        if (is_string($floor) && str_ends_with($floor, '+')) {
            $number = rtrim($floor, '+');
            return is_numeric($number) && (int)$number > 0;
        }

        return false;
    }

    /**
     * @param mixed $area
     * @return bool
     */
    private function isValidAreaFormat($area): bool
    {
        if (is_numeric($area)) {
            return (float)$area > 0;
        }

        if (is_array($area)) {
            foreach ($area as $value) {
                if (!is_numeric($value) || (float)$value <= 0) {
                    return false;
                }
            }
            return true;
        }

        if (is_string($area) && str_contains($area, ',')) {
            $values = array_map('trim', explode(',', $area));
            foreach ($values as $value) {
                if (str_ends_with($value, '+')) {
                    $number = rtrim($value, '+');
                    if (!is_numeric($number) || (float)$number <= 0) {
                        return false;
                    }
                } elseif (str_contains($value, '-')) {
                    $parts = explode('-', $value, 2);
                    if (count($parts) === 2) {
                        $min = trim($parts[0]);
                        $max = trim($parts[1]);
                        if ($min === '' && $max === '') {
                            return false;
                        }
                        if ($min !== '' && (!is_numeric($min) || (float)$min <= 0)) {
                            return false;
                        }
                        if ($max !== '' && (!is_numeric($max) || (float)$max <= 0)) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    if (!is_numeric($value) || (float)$value <= 0) {
                        return false;
                    }
                }
            }
            return true;
        }

        if (is_string($area) && str_ends_with($area, '+')) {
            $number = rtrim($area, '+');
            return is_numeric($number) && (float)$number > 0;
        }

        if (is_string($area) && str_contains($area, '-')) {
            $parts = explode('-', $area, 2);
            if (count($parts) === 2) {
                $min = trim($parts[0]);
                $max = trim($parts[1]);
                if ($min === '' && $max === '') {
                    return false;
                }
                if ($min !== '' && (!is_numeric($min) || (float)$min <= 0)) {
                    return false;
                }
                if ($max !== '' && (!is_numeric($max) || (float)$max <= 0)) {
                    return false;
                }
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $finishing
     * @return bool
     */
    private function isValidFinishingFormat($finishing): bool
    {
        if (is_string($finishing)) {
            return !empty(trim($finishing));
        }

        if (is_array($finishing)) {
            foreach ($finishing as $value) {
                if (!is_string($value) || empty(trim($value))) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * @param mixed $builtYear
     * @return bool
     */
    private function isValidBuiltYearFormat($builtYear): bool
    {
        if (is_string($builtYear)) {
            if (in_array($builtYear, ['Сдан', 'Позднее'])) {
                return true;
            }
            if (is_numeric($builtYear)) {
                $year = (int)$builtYear;
                return $year >= 1900 && $year <= 2100;
            }
            if (str_contains($builtYear, ',')) {
                $values = array_map('trim', explode(',', $builtYear));
                foreach ($values as $value) {
                    if (!in_array($value, ['Сдан', 'Позднее']) && (!is_numeric($value) || (int)$value < 1900 || (int)$value > 2100)) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        }

        if (is_numeric($builtYear)) {
            $year = (int)$builtYear;
            return $year >= 1900 && $year <= 2100;
        }

        if (is_array($builtYear)) {
            foreach ($builtYear as $value) {
                if (is_string($value)) {
                    if (!in_array($value, ['Сдан', 'Позднее'])) {
                        return false;
                    }
                } elseif (is_numeric($value)) {
                    $year = (int)$value;
                    if ($year < 1900 || $year > 2100) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * @param mixed $metroTime
     * @return bool
     */
    private function isValidMetroTimeFormat($metroTime): bool
    {
        if (is_numeric($metroTime)) {
            return (int)$metroTime >= 0;
        }

        if (is_array($metroTime)) {
            foreach ($metroTime as $value) {
                if (!is_numeric($value) || (int)$value < 0) {
                    return false;
                }
            }
            return true;
        }

        if (is_string($metroTime) && str_contains($metroTime, ',')) {
            $values = array_map('trim', explode(',', $metroTime));
            foreach ($values as $value) {
                if (str_ends_with($value, '+')) {
                    $number = rtrim($value, '+');
                    if (!is_numeric($number) || (int)$number < 0) {
                        return false;
                    }
                } elseif (str_contains($value, '-')) {
                    $parts = explode('-', $value, 2);
                    if (count($parts) === 2) {
                        $min = trim($parts[0]);
                        $max = trim($parts[1]);
                        if ($min === '' && $max === '') {
                            return false;
                        }
                        if ($min !== '' && (!is_numeric($min) || (int)$min < 0)) {
                            return false;
                        }
                        if ($max !== '' && (!is_numeric($max) || (int)$max < 0)) {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    if (!is_numeric($value) || (int)$value < 0) {
                        return false;
                    }
                }
            }
            return true;
        }

        if (is_string($metroTime) && str_ends_with($metroTime, '+')) {
            $number = rtrim($metroTime, '+');
            return is_numeric($number) && (int)$number >= 0;
        }

        if (is_string($metroTime) && str_contains($metroTime, '-')) {
            $parts = explode('-', $metroTime, 2);
            if (count($parts) === 2) {
                $min = trim($parts[0]);
                $max = trim($parts[1]);
                if ($min === '' && $max === '') {
                    return false;
                }
                if ($min !== '' && (!is_numeric($min) || (int)$min < 0)) {
                    return false;
                }
                if ($max !== '' && (!is_numeric($max) || (int)$max < 0)) {
                    return false;
                }
                return true;
            }
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
