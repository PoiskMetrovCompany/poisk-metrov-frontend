<?php

namespace App\CRM\Commands;

use App\CRM\cURL;
use stdClass;

class GetLead extends AbstractCRMCommand
{
    private string $parameters;

    public function __construct(int $lead_id, string $city)
    {
        //https://docs.google.com/document/d/1TLXZxy2PR1_MZwpKROGV_TMTRbFEltrMOTtWWFyK_WA/edit#heading=h.tb0bs5hqg5ob
        parent::__construct('/openapi/v1/lead/get/', $city);

        $requestDataObject = new stdClass();
        $requestDataObject->lead_id = $lead_id;
        $this->parameters = json_encode(['request' => $requestDataObject]);
    }

    public function execute()
    {
        $result = cURL::sendRequest($this->URL, $this->parameters);
        return transliterator_create('Hex-Any')->transliterate(json_encode($result));
    }
}