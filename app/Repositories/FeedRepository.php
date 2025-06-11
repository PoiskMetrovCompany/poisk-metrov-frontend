<?php

namespace App\Repositories;

use App\Core\Interfaces\Repositories\FeedRepositoryInterface;
use App\Models\Apartment;
use App\Models\Builder;
use App\Models\Location;
use App\Models\ResidentialComplex;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

final class FeedRepository implements FeedRepositoryInterface
{
    public function __construct(
        protected Location $locationModel,
        protected ResidentialComplex $residentialComplexModel,
        protected Apartment $apartmentModel,
        protected Builder $builderModel,
    ) {

    }

    private function dataApartmentFormatter(array $attributes)
    {
        return [
            'key'                   => $attributes['apartments']['_id'],
            'offer_id'              => $attributes['apartments']['_id'],
            'complex_id'            => null,
            'apartment_type'        => $attributes['apartments']['_id'],
            'renovation'            => $attributes['finishing']['name'],
            'balcony'               => (bool)$attributes['apartments']['area_balconies_total'],
            'bathroom_unit'         => $attributes['apartments']['wc_count'],
            'floor'                 => $attributes['apartments']['floor'],
            'floors_total'          => $attributes['apartments']['floors'],
            'apartment_number'      => $attributes['apartments']['number'],
            'building_materials'    => null,
            'building_state'        => null,
            'building_phase'        => "Очередь {$attributes['building']['queue']}",
            'building_section'      => null,
            'latitude'              => $attributes['detail']['geometry']['coordinates'][0] ?? null,
            'longitude'             => $attributes['detail']['geometry']['coordinates'][1] ?? null,
            'ready_quarter'         => null,
            'built_year'            => DateTime::createFromFormat('Y-m-d\TH:i:s.uO', $attributes['building']['deadline'])->format('Y'),
            'plan_URL'              => $attributes['apartments']['plan'][0],
            'ceiling_height'        => $attributes['apartments']['height'],
            'room_count'            => $attributes['apartments']['room'],
            'price'                 => $attributes['apartments']['price'],
            'area'                  => $attributes['apartments']['area_total'],
            'living_space'          => $attributes['apartments']['area_given'],
            'kitchen_space'         => $attributes['apartments']['area_kitchen'],
            'floor_plan_url'        => null,
            'windows_directions'    => null,
            'meta'                  => '[{"name": "description", "content": "Купите квартиру в ЖК ' . $attributes['apartments']['block_name'] . ' без комиссии и переплат. Продажа квартир в ' . $attributes['apartments']['block_name'] . '"}]',
            'feed_source'           => 'TrendAgent',
            'head_title'            => "Продажа квартиры" . ($attributes['apartments']['room'] === 0 ? "-студии" : "") . " {$attributes['apartments']['area_total']} м² по цене {$attributes['apartments']['price']} млн ₽ по адресу: {$attributes['detail']['address'][0]}, д. 33",
            'h1'                    => "Квартира" . ($attributes['apartments']['room'] === 0 ? "-студии" : "") . " в ЖК {$attributes['apartments']['block_name']}, {$attributes['apartments']['area_total']} м², этаж {$attributes['apartments']['floor']}",
        ];
    }

    /**
     * @param string $district
     * @return int
     */
    private function dataLocationFormatter(string $district): int
    {
        $dataSearched = [
            'country'   => 'Россия',
            'capital' => Session::get('city'),
            'district' => $district
        ];
        $model = $this->locationModel::where($dataSearched)->first();
        if (!$model) {
            $model = $this->locationModel::create([
                ...$dataSearched,
                'region' => Session::get('city'),
                'code' => Str::slug(Session::get('city'), '-'),
                'locality' => $district
            ]);
        }
        return $model->id;
    }

    private function dataResidentialComplexFormatter(array $attributes)
    {
        return [
            'key'                   => $attributes['detail']['_id'],
            'code'                  => strtolower(Str::slug($attributes['apartments']['block_name'])),
            'old_code'              => strtolower(Str::slug($attributes['apartments']['block_name'])),
            'name'                  => $attributes['apartments']['block_name'],
            'builder'               => $attributes['builder']['name'],
            'description'           => $attributes['detail']['description'],
            'latitude'              => $attributes['detail']['geometry']['coordinates'][0] ?? null,
            'longitude'             => $attributes['detail']['geometry']['coordinates'][1] ?? null,
            'location_id'           =>  $this->dataLocationFormatter($attributes['region']['name']), //(Location::where(['capital' => 'Санкт-Петербург', 'district' => $attributes['region']['name']])->first())->id,
            'address'               => $attributes['detail']['address'][0] ?? null,
            'metro_station'         => $attributes['subway'][0]['name'],
            'metro_time'            => $attributes['subway'][0]['distance_time'],
            'metro_type'            => null,
            'infrastructure'        => null,
            'parking'               => null,
            'panorama'              => null,
            'corpuses'              => null,
            'meta'                  => '[{"name": "description", "content": "Купите квартиру в ЖК ' . $attributes['apartments']['block_name'] . ' без комиссии и переплат. Продажа квартир в ' . $attributes['apartments']['block_name'] . ' по цене от "}]',
            'elevator'              => null,
            'primary_material'      => null,
            'floors'                => null,
            'primary_ceiling_height'=> null,
            'on_main_page'          => null,
            'head_title'            => "Купить квартиру в ЖК {$attributes['apartments']['block_name']} в {$attributes['region']['name']} от застройщика, цены на квартиры, планировки",
            'h1'                    => "ЖК {$attributes['apartments']['block_name']}",
        ];
    }

    public function dataBuilderFormatter(array $attributes)
    {
        return [
            'key'           => $attributes['builder']['_id'],
            'construction'  =>  $attributes['apartments']['block_name'],
            'builder'       => $attributes['builder']['name'],
            'city'          => Session::get('city'),
        ];
    }

    public function getFeedApartmentsData(string $feedKey)
    {
        $model = $this->apartmentModel::query();
        $model->where(['key' => $feedKey]);
        return $model->get();
    }

    public function store(array $attributes)
    {
        $modelBuilder = $this->builderModel::query();
        $dataBuilder = $this->dataBuilderFormatter($attributes);
        $modelBuilder->updateOrCreate(
            ['builder' => $dataBuilder['builder']],
            [...$dataBuilder]
        );

        $modelResidentialComplex = $this->residentialComplexModel::query();
        $dataResidentialComplex = $this->dataResidentialComplexFormatter($attributes);
        $modelResidentialComplex->updateOrCreate(
//            ['key' => $attributes['detail']['_id']],
            ['code' => $dataResidentialComplex['code']],
            [...$dataResidentialComplex]
        );

        $modelApartment = $this->apartmentModel::query();
        $modelApartment->updateOrCreate(
            ['key' => $attributes['apartments']['_id']],
            [...$this->dataApartmentFormatter($attributes)]
        );

        return $modelApartment;
    }
}
