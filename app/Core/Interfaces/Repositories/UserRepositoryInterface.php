<?php

namespace App\Core\Interfaces\Repositories;

use App\Core\Interfaces\Repositories\Queries\ListQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByKeyQueryInterface;
use App\Core\Interfaces\Repositories\Queries\FindByIdQueryInterface;

// TODO: пока только запрос на список и чтение, потом добавлю другие варианты запросов
/**
 * @template TRepository
 */
interface UserRepositoryInterface extends ListQueryInterface, FindByIdQueryInterface, FindByKeyQueryInterface
{

}
