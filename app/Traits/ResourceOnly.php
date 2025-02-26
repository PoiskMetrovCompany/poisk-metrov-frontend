<?php

namespace App\Traits;

trait ResourceOnly
{
    use ResourceFieldFilter;

    public function only(array $fields)
    {
        return $this->filterFields($fields, $this->toArray(null));
    }
}