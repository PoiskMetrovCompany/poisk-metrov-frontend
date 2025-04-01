<?php

namespace App\Core\Interfaces\Services;

use Illuminate\Support\Collection;

interface LocationServiceInterface
{
    /**
     * @return Collection
     */
    public function getRegionsFromXMLFiles(): Collection;
}
