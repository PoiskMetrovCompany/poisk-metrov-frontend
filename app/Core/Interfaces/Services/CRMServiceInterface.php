<?php

namespace App\Core\Interfaces\Services;

interface CRMServiceInterface
{
    /**
     * @param string $phone
     * @param string $comment
     * @param string $city
     * @param string|null $name
     */
    public function createLead(string $phone, string $comment, string $city, string|null $name = null);
}
