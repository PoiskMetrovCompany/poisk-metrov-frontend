<?php

namespace App\Core\Common;

final class CatalogueStatisticTemplate
{
    public static function iconData(): array
    {
        return [
            ""
        ];
    }
    public static function getApartmentTemplate(): array
    {
        return ["title" => "", "count_prepositions" => 0, "icon" => ""];
    }

    public static function getResidentialComplexTemplate(): array
    {
        return ["title" => "", "count_prepositions" => 0, "icon" => ""];
    }
    public static function getTemplate(): array
    {
        return [
            'type' => "",
            "meta" => [
                ["title" => "Все проекты", "count" => 0],
                ["title" => "Популярные", "count" => 0],
                ["title" => "Акции", "count" => 0],
            ],
            "attributes" => [

            ],
        ];
    }
}
