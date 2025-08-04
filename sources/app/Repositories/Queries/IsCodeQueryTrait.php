<?php

namespace App\Repositories\Queries;


use Illuminate\Database\Eloquent\Model;

trait IsCodeQueryTrait
{
    public function isCode(string $code): bool
    {
        return $this->model->where('code', $code)->exists();
    }
}
