<?php

namespace App\Core\Interfaces\Services;

use App\Core\DTO\CatalogFilterDTO;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;

/**
 * @template TService
 */
interface FilterServiceInterface
{
    /**
     * @param string $entityType
     * @return ResidentialComplexRepositoryInterface|ApartmentRepositoryInterface
     */
    public function entityBuild(string $entityType): ResidentialComplexRepositoryInterface | ApartmentRepositoryInterface;

    /**
     * @param CatalogFilterDTO $attributes
     * @return array
     */
    public function execute(CatalogFilterDTO $attributes): array;
}
