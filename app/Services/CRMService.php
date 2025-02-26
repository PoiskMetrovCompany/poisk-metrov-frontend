<?php

namespace App\Services;

use App\CRM\Commands\CreateLead;

/**
 * Class CRMService.
 */
class CRMService
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
