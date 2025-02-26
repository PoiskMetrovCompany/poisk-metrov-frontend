<?php

namespace App\DropdownData;

class ApartmentTypeDropdownData extends DropdownData
{
    public function __construct()
    {
        parent::__construct(['Только апартаменты', 'Исключить апартаменты'], '=', 'apartment_type');

        $this->data['Только апартаменты']['value'] = 'Апартамент';
        $this->data['Исключить апартаменты']['value'] = 'Апартамент';
        $this->data['Только апартаменты']['condition'] = '=';
        $this->data['Исключить апартаменты']['condition'] = '<>';
        $this->allowMultiple = false;
    }
}