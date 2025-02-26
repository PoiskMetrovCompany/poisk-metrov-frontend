<?php

namespace App\CRM\Commands;

use App\CRM\cURL;
use stdClass;

class UpdateLead extends AbstractCRMCommand
{
    private string $parameters;

    public function __construct(int $lead_id, array $fields, string $city)
    {
        //https://docs.google.com/document/d/1TLXZxy2PR1_MZwpKROGV_TMTRbFEltrMOTtWWFyK_WA/edit#heading=h.fu26vhl3opph
        parent::__construct('/openapi/v1/lead/updateLeadValue/', $city);

        $requestDataObject = new stdClass();
        $requestDataObject->lead_id = $lead_id;
        $requestDataObject->fields = $fields;
        $this->parameters = json_encode(['request' => $requestDataObject]);
    }

    public function execute()
    {
        $result = cURL::sendRequest($this->URL, $this->parameters);
        return transliterator_create('Hex-Any')->transliterate(json_encode($result));
    }
}