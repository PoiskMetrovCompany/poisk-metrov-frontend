<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Build\FindQueryBuilderInterface;
use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByKeyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindOfferIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\JoinQueryInterface;
use App\Core\Interfaces\Repositories\Queries\NotInListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByOfferIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByInComplexIdQueryInterface;

/**
 * @template TRepository
 */
interface ApartmentRepositoryInterface extends

    ListQueryInterface,
    FindByIdQueryInterface,
    FindByKeyQueryInterface,
    FindOfferIdQueryInterface,
    JoinQueryInterface,
    NotInListQueryInterface,
    IsExistsQueryInterface,
    FindByOfferIdQueryInterface,
    FindQueryBuilderInterface,
    FindByInComplexIdQueryInterface
{
    /**
     * @param string $cityCode
     * @return array
     */
    public function getDefaultSelections(string $cityCode): array;
}
