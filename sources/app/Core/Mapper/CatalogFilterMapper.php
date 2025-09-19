<?php

namespace App\Core\Mapper;

use App\Core\DTO\CatalogFilterDTO;

final class CatalogFilterMapper
{
    /**
     * @param array $attributes
     * @return CatalogFilterDTO
     */
    public static function fromRequest(array $attributes): CatalogFilterDTO
    {
        $dto = new CatalogFilterDTO();

        $dto->entityType = $attributes['entity_type'] ?? null;

        $dto->countRooms = self::getValidatedValue($attributes, 'count_rooms');
        $dto->pricing = self::getValidatedValue($attributes, 'pricing');
        $dto->floors = self::convertToInt(self::getValidatedValue($attributes, 'floors'));
        $dto->areaTotal = self::getValidatedValue($attributes, 'area_total');
        $dto->livingArea = self::getValidatedValue($attributes, 'living_area');
        $dto->ceilingHeight = self::getValidatedValue($attributes, 'ceiling_height');
        $dto->layout = self::getValidatedValue($attributes, 'layout');
        $dto->finishing = self::getValidatedValue($attributes, 'finishing');
        $dto->bathroom = self::getValidatedValue($attributes, 'bathroom');
        $dto->apartments = self::getValidatedValue($attributes, 'apartments');
        $dto->peculiarities = self::getValidatedValue($attributes, 'peculiarities');
        $dto->montageType = self::getValidatedValue($attributes, 'montage_type');
        $dto->developer = self::getValidatedValue($attributes, 'developer');
        $dto->dueDate = self::getValidatedValue($attributes, 'due_date');
        $dto->toMetro = self::convertToInt(self::getValidatedValue($attributes, 'to_metro'));
        $dto->elevator = self::getValidatedValue($attributes, 'elevator');
        $dto->floorCounts = self::getValidatedValue($attributes, 'floor_counts');
        $dto->parking = self::getValidatedValue($attributes, 'parking');
        $dto->search = self::getValidatedValue($attributes, 'search');

        return $dto;
    }

    /**
     * @deprecated fromRequest()
     */
    public static function mapFromRequest(array $attributes, CatalogFilterDTO $dto): CatalogFilterDTO
    {
        $mappedDto = self::fromRequest($attributes);

        foreach (get_object_vars($mappedDto) as $property => $value) {
            $dto->$property = $value;
        }

        return $dto;
    }

    /**
     * @param array $attributes
     * @param string $key
     * @return mixed|null
     */
    private static function getValidatedValue(array $attributes, string $key)
    {
        $value = $attributes[$key] ?? null;

        if ($value === null || $value === '' || $value === []) {
            return null;
        }

        if (is_string($value) && trim($value) === '') {
            return null;
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return int|null
     */
    private static function convertToInt($value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        return null;
    }
}
