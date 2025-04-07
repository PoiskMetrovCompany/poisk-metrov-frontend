<?php

namespace App\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

trait FindByPinCodeQueryTrait
{
    public function findByPinCode(mixed $pinCode): ?Model
    {
        return $this->model::where('pincode', $pinCode)->first();
    }
}
