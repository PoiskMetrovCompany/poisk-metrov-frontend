<?php

namespace App\Repositories\Queries;

trait FindByIdQueryTrait
{
    public function findById(int $id)
    {
        return $this->model::find($id);
    }
}
