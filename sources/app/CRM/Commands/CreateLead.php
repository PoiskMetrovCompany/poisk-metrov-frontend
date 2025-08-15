<?php

namespace App\CRM\Commands;

use App\CRM\cURL;
use stdClass;

class CreateLead extends AbstractCRMCommand
{
    private string $parameters;

    //id field (not crm_id)
    private array $inboxTypes = ['novosibirsk' => 853931, 'st-petersburg' => 1299682];

    public function __construct($name, $phone, $comment, $city, $contactType = null)
    {
        parent::__construct('/openapi/v1/lead/set/', $city);

        $commendFieldID = 312755;
        $whereFromID = 312752;

        $requestDataObject = new stdClass();
        $requestDataObject->method = 'create';

        //Look for types in all data
        if (key_exists($city, $this->inboxTypes)) {
            $requestDataObject->inbox_type_id = $this->inboxTypes[$city];
        } else {
            $requestDataObject->inbox_type_id = $this->inboxTypes['novosibirsk'];
        }

        $requestDataObject->values = new stdClass();
        $requestDataObject->values->name = $name;
        $requestDataObject->values->phone = $phone;
        $requestDataObject->values->comment = $comment;

        $requestDataObject->values->custom = [];
        $requestDataObject->values->custom[] = (object) ['input_id' => $commendFieldID, 'value' => 'Пользователь созданный через `API'];
        $requestDataObject->values->custom[] = (object) ['input_id' => $whereFromID, 'value' => 'Веб-сайт'];

        if ($contactType != null) {
            $requestDataObject->contact_data = [];
            $requestDataObject->contact_data[] = (object) ['type' => $contactType, 'value' => $phone];
        }

        $this->parameters = json_encode(['request' => $requestDataObject]);
    }

    public function execute($encode = true, $addParameters = true)
    {
        $result = cURL::sendRequest($this->URL, $this->parameters);

//        if ($addParameters) {
//            $result->parameters = transliterator_create('Hex-Any')->transliterate($this->parameters);
//        }

        if ($encode) {
            return json_encode($result);
        } else {
            return $result;
        }
    }
}
