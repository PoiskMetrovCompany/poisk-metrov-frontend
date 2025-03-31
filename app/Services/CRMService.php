<?php

namespace App\Services;

use App\Core\Interfaces\Services\CRMServiceInterface;
use App\CRM\Commands\CreateLead;

/**
 * @package App\Services
 * @implements CRMServiceInterface
 */
class CRMService implements CRMServiceInterface
{
    public function createLead(string $phone, string $comment, string $city, string|null $name = null)
    {
        if ($name == null) {
            $createLead = new CreateLead("САЙТ!", $phone, $comment, $city);
        } else {
            $createLead = new CreateLead($name, $phone, $comment, $city);
        }

        return $createLead->execute();
    }
}
