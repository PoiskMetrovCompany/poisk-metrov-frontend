<?php

namespace App\Core\Interfaces\Repositories\Queries;

use Illuminate\Database\Eloquent\Model;

interface IsCodeQueryInterface
{
    /**
     * @param string $code
     * @return bool
     */
    public function isCode(string $code): bool;
}
