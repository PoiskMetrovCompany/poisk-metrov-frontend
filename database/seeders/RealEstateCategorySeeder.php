<?php

namespace Database\Seeders;

use App\Models\ResidentialComplex;
use App\Models\ResidentialComplexCategory;
use App\Models\ResidentialComplexCategoryPivot;
use App\Services\CityService;
use App\Services\TextService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealEstateCategorySeeder extends Seeder
{
    private array $categoriesByCity = ['novosibirsk' => [
        'Элитные' =>
            [
                'Flora & fauna',
                'Мылзавод',
                'Прованс',
                'Горская лагуна',
                'Balance',
                'Willart',
                'Чикаго',
                'Нобель',
                'Oscar',
                'Юнити центр',
                'Наследие',
                'Берлин',
                'Новый кедровый',
                'Тихомиров',
                'Грандо',
            ],
        'Новостройки у воды' =>
            [
                'Марсель-2',
                'Европейский берег',
                'Чернышевский ',
                'Беринг',
                'Ясный берег',
                'Весна',
                'Аквамарин',
                'Горская лагуна',
                'Скандинавские кварталы',
            ],
        'Хорошо под сдачу' =>
            [
                'Чернышевский ',
                'Тихомиров',
                'Никольский парк',
                'Притяжение',
                'IQ Aparts',
                'Лэнд-Лорд',
                'Тайм Парк Апартаменты',
                'GAGARIN CITY',
                'Аэрон',
            ],
        'Быстрое заселение' =>
            [
                'Расцветай на Обской',
                'Расцветай на Игарской',
                'Чернышевский ',
                'Аквамарин',
                'Воздух',
                'Державина 50',
                'Дивногорский',
                'Цветной бульвар',
                'Кольца ',
                'На Никитина',
                'Поколение',
                'притяжение',
                'расцветай на зорге',
                'расцветай на красном',
                'Тихомиров',
                'Цивилизация',
            ],
        'Рядом с метро' =>
            [
                'Расцветай на Красном',
                'Тайм Сквер',
                'RICHMOND Residence',
                'Горская лагуна',
                'Саура',
                'Мылзавод',
                'Тихомиров',
                'Воздух',
                'Державина 50',
                'Наследие',
                'Прованс',
            ],
        'Рядом парк и лес' =>
            [
                'Страна Бареговая',
                'Flora & fauna',
                'Флагман Холл',
                'Первый на Есенина',
                'Первый на Рябиновой',
                'Ключ-Камышенское плато',
                'Скандинавские кварталы',
                'Новый кедровый',
                'Авангард парк',
                'Новелла',
                'Академ klubb',
            ],
    ]];


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $textService = TextService::getFromApp();
        $cityService = CityService::getFromApp();
        // $cities = $cityService->possibleCityCodes;
        $cities = ['novosibirsk'];

        foreach ($cities as $city) {
            foreach ($this->categoriesByCity[$city] as $cityCategory => $realEstateNameList) {
                $category = ResidentialComplexCategory::where('category_name', $cityCategory)->first();

                if ($category == null) {
                    $category = ResidentialComplexCategory::create(['category_name' => $cityCategory]);
                }

                foreach ($realEstateNameList as $realEstateName) {
                    $transliteratedName = $textService->transliterate($realEstateName);
                    $building = ResidentialComplex::where('code', $transliteratedName)->first();

                    if ($building == null) {
                        $building = ResidentialComplex::where('name', $realEstateName)->first();
                    }

                    if ($building == null) {
                        echo "$realEstateName not found!" . PHP_EOL;
                        continue;
                    }

                    $data = ['category_id' => $category->id, 'complex_id' => $building->id];

                    if (! ResidentialComplexCategoryPivot::where($data)->exists()) {
                        ResidentialComplexCategoryPivot::create($data);
                    }
                }

                echo 'There are ' . $category->residentialComplexes()->get()->count() . " residential complexes in category {$category->category_name}" . PHP_EOL;
            }
        }
    }
}
