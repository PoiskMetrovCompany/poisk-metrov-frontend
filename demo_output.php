<?php

// Демонстрация того, как будет выглядеть новый вывод

$sampleOutput = [
    "identifier" => "7b8c2ed6-22df-4c6c-8e84-4c81de266b08",
    "attributes" => [
        "id" => 1,
        "location_key" => "06c52ed9-83c2-11f0-a013-10f60a82b815",
        "key" => "06d561aa-83c2-11f0-a013-10f60a82b815",
        "code" => "everest",
        "old_code" => "everest",
        "name" => "Эверест",
        "builder" => "Эверест-Н",
        "description" => "Тихое и спокойное месторасположение...",
        "latitude" => 54.9893875762,
        "longitude" => 83.0423160298,
        "address" => "Пролетарская ул., д. 271/5",
        "metro_station" => "Речной вокзал",
        "metro_time" => 12,
        "metro_type" => "by_transport",
        "meta" => "[{\"name\": \"description\", \"content\": \"Купите квартиру...\"}]",
        "head_title" => "Купить квартиру в ЖК Эверест в Новосибирске...",
        "h1" => "ЖК Эверест",
        "ready_quarter" => 2,
        "built_year" => 2025,
        "residential_min_price" => 2500000, // <-- НОВОЕ ПОЛЕ
        "includes" => [
            [
                "type" => "apartment",
                "attributes" => [
                    [
                        "study" => [
                            ["min_price" => 2800000],
                            ["id" => 123, "price" => 2800000]
                        ],
                        "1_rooms" => [
                            ["min_price" => 3200000],
                            ["id" => 124, "price" => 3200000]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

echo "Пример вывода ЖК с новым полем residential_min_price:\n\n";
echo json_encode($sampleOutput, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
