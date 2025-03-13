<?php

namespace App\Repositories\Queries;

trait ReadQueryTrait
{
    public function read(int|string $identifier)
    {
        return gettype($identifier) === 'integer'
            ? $this->model::find($identifier)
            : $this->model::where(['key' => $identifier])->first();
    }
}
