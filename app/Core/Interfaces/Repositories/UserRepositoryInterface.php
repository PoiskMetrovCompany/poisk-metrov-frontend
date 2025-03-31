<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\IsExistsQueryInterface;
use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByKeyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByChatTokenQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByApiTokenQueryInterface;

// TODO: пока только запрос на список и чтение, потом добавлю другие варианты запросов
/**
 * @template TRepository
 */
interface UserRepositoryInterface extends
    ListQueryInterface,
    FindByIdQueryInterface,
    FindByKeyQueryInterface,
    IsExistsQueryInterface,
    FindByChatTokenQueryInterface,
    FindByApiTokenQueryInterface
{

}
