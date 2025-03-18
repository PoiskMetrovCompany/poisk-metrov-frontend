<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\FileRepositoryInterface;
use App\Models\File;
use App\Repositories\Queries\FindByIdQueryTrait;
use App\Repositories\Queries\FindByKeyQueryTrait;
use App\Repositories\Queries\ListQueryTrait;
use App\Repositories\Queries\StoreQueryTrait;
use Illuminate\Database\Eloquent\Model;

final class FileRepository implements FileRepositoryInterface
{
    use ListQueryTrait;
    use StoreQueryTrait;
    use FindByIdQueryTrait;
    use FindByKeyQueryTrait;

    protected Model $model;

    public function __construct()
    {
        $this->model = new File();
    }
}
