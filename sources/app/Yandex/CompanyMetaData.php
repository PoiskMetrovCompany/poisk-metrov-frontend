<?php

namespace App\Yandex;

use Illuminate\Support\Collection;

class CompanyMetaData
{
    public string $id;
    public string $name;
    public string $address;
    public Collection $categories;
    public Collection $phones;
    public Hours $hours;
    public Collection $features;
    public string $url;

    public function __construct($companyMetaData) {
        $this->id = $companyMetaData->id;
        $this->name = $companyMetaData->name;
        $this->address = $companyMetaData->address;
        $this->categories = collect([]);

        if (property_exists($companyMetaData, 'url')) {
            $this->url = $companyMetaData->url;
        }

        foreach ($companyMetaData->Categories as $category) {
            $this->categories->push(new Category($category));
        }

        $this->hours = new Hours($companyMetaData->Hours);
    

        if (property_exists($companyMetaData, 'Phones')) {
            $this->phones = collect([]);

            foreach ($companyMetaData->Phones as $phone) {
                $this->phones->push(new Phone($phone));
            }
        }

        if (property_exists($companyMetaData, 'Features')) {
            $this->features = collect([]);

            foreach ($companyMetaData->Features as $feature) {
                $this->features->push(new Feature($feature));
            }
        }
    }
}