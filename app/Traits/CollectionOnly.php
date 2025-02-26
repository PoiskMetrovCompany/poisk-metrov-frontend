<?php

namespace App\Traits;

trait CollectionOnly
{
    use ResourceFieldFilter;

    public function only(array $fields)
    {
        foreach ($this->collection as $key => $item) {
            $this->collection[$key] = $this->filterFields($fields, $item->toArray(null));
        }
        return $this->collection;
    }
}