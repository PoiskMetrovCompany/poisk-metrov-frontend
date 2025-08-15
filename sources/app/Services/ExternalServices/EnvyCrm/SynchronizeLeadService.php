<?php

namespace App\Services\ExternalServices\EnvyCrm;

use App\Core\Interfaces\Services\SynchronizeLeadServiceInterface;

/**
 * @template TService
 */
final class SynchronizeLeadService implements SynchronizeLeadServiceInterface
{
    protected function connection()
    {
        // TODO: сделать коннект и убрать App/CRM/*
    }
    public function addLead()
    {

    }
}
