<?php

namespace App\Models;

use App\Observers\ResidentialComplexObserver;
use App\Services\CityService;
use App\Http\Resources\GalleryResource;
use App\TextFormatters\PriceTextFormatter;
use Auth;
use App\DropdownData\ApartmentTypeDropdownData;
use App\DropdownData\AreaDropdownData;
use App\DropdownData\FinishingDropdownData;
use App\DropdownData\FloorsDropdownData;
use App\DropdownData\KitchenDropdownData;
use App\DropdownData\MortgageDropdownData;
use App\DropdownData\PricesDropdownData;
use App\DropdownData\RoomsDropdownData;
use App\DropdownData\ToiletDropdownData;
use App\DropdownData\YearsDropdownData;
use App\DropdownData\CorpusDropdownData;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Location;
use App\Models\Apartment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

#[ObservedBy([ResidentialComplexObserver::class])]
class ResidentialComplex extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'Amenity' => ['main_table_value' => 'id', 'linked_table_value' => 'complex_id'],
        'Apartment' => ['main_table_value' => 'id', 'linked_table_value' => 'complex_id'],
        'BestOffer' => ['main_table_value' => 'code', 'linked_table_value' => 'complex_code'],
        'BuildingProcess' => ['main_table_value' => 'id', 'linked_table_value' => 'complex_id'],
        'Doc' => ['main_table_value' => 'id', 'linked_table_value' => 'complex_id'],
        'Gallery' => ['main_table_value' => 'id', 'linked_table_value' => 'building_id'],
        'ResidentialComplex' => ['main_table_value' => 'id', 'linked_table_value' => 'building_id'],
        'ResidentialComplexCategoryPivot' => ['main_table_value' => 'id', 'linked_table_value' => 'complex_id'],
        'SpriteImagePosition' => ['main_table_value' => 'id', 'linked_table_value' => 'building_id'],
        'UserFavoriteBuilding' => ['main_table_value' => 'code', 'linked_table_value' => 'complex_code'],
        'Location' => ['main_table_value' => 'location_key', 'linked_table_value' => 'key'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'code',
        'old_code',
        'name',
        'builder',
        'infrastructure',
        'description',
        'latitude',
        'longitude',
        'location_key',
        'address',
        'metro_station',
        'metro_time',
        'metro_type',
        'meta',
        'head_title',
        'h1'
    ];

    public static $searchableFields = [
        'name',
        'builder',
        'address',
        'metro_station',
        'metro_time',
        'metro_type',
        'metro_time-to',
        'metro_time-from'
    ];

    /**
     * @var string[]
     * Застройщики которые не разрешают рекламу
     * Актуальный список тут: https://nsk.nmarket.pro/media
     */
    public static $privateBuilders = [
        'Эверест-Н',
        'Стрижи',
        'Страна Девелопмент',
        'СМС',
        'СЛК',
        'Скандиа',
        'Сибирьинвест',
        'Сибирские Жилые Кварталы',
        'СЗ Спектр',
        'СЗ Снегири',
        'СЗ Сергиев Пассаж',
        'СЗ Мегаполис',
        'СЗ МЕГА АПАРТС',
        'СЗ ЖК на Вилюйской',
        'СДС-Финанс',
        'РимЭлитСтрой',
        'Первый Строительный',
        'Новосибирский квартал',
        'Камея',
        'Дом-Строй',
        'Домология',
        'Дом Солнца',
        'ГринАгроСтрой',
        'ГК Эталон',
        'ГК СССР',
        'ГК Союз',
        'ГК КПД-Газстрой',
        'ГК Гелеон',
        'ГК ВербаКапитал',
        'Брусника',
        'Бови',
        'Астон. Стройтрест 43',
        'АКД-Мета',
        'АКВА СИТИ',
        'Академия',
        'VIRA',
        'SD GROUP',
    ];

    public function getGalleryImages(int $limit = 5, bool $diskOnly = false): array
    {
        $directory = "galleries/{$this->location->code}/{$this->code}";
        $gallery = Storage::disk('root')->files($directory);

        if (count($gallery) >= 2) {
            [$gallery[0], $gallery[1]] = [$gallery[1], $gallery[0]];
        }

        if (count($gallery) < $limit && ! $diskOnly) {
            $rawGallery = GalleryResource::collection($this->gallery)->toArray(new Request());

            foreach ($rawGallery as $item) {
                $gallery[] = $item["image_url"];
            }
        }

        $gallery = array_slice($gallery, 0, $limit);

        return $gallery;
    }

    public function isFavorite()
    {
        if (key_exists('favoriteBuildings', $_COOKIE)) {
            $split = explode(',', $_COOKIE['favoriteBuildings']);

            if (in_array($this->code, $split)) {
                return true;
            }
        }

        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return UserFavoriteBuilding::where(['user_id' => $user->id, 'complex_code' => $this->code])->exists();
    }

    public function createApartmentSpecifics($recreate = false)
    {
        if (! $recreate && $this->hasMany(ResidentialComplexApartmentSpecifics::class, 'building_id')->count() > 0) {
            return;
        }

        ResidentialComplexApartmentSpecifics::where('building_id', $this->id)->delete();

        for ($i = 0; $i < 10; $i++) {
            $selection = $i == 0 ?
                $this->apartments()->select()->where('apartment_type', 'Студия') :
                $this->apartments()->select()->where([['apartment_type', '<>', 'Студия'], ['room_count', '=', $i]]);

            if ($selection == null) {
                continue;
            }

            $minPrice = $selection->min('price');

            if ($minPrice <= 0) {
                continue;
            }

            $currentApartmentMinData = [];
            $currentApartmentMinData['building_id'] = $this->id;
            $currentApartmentMinData['starting_price'] = $minPrice;
            $currentApartmentMinData['starting_area'] = $selection->min('area');
            $currentApartmentMinData['count'] = $selection->count();
            $currentApartmentMinData['display_name'] = $i == 0 ? "студ." : "{$i}к. кв.";

            ResidentialComplexApartmentSpecifics::create($currentApartmentMinData);
        }
    }

    public function getMinPricePerMeter()
    {
        $pricesPerMeter = new Collection();

        foreach ($this->apartments as $apartment) {
            $pricesPerMeter[] = floor($apartment->price / $apartment->area);
        }

        return $pricesPerMeter->min();
    }

    public function getSearchData()
    {
        $apartmentData = $this->apartments()->get();
        $data = [];
        $dropdownData = [];
        $dropdownData['years'] = new YearsDropdownData($apartmentData);
        $dropdownData['rooms'] = new RoomsDropdownData($apartmentData);
        $dropdownData['prices'] = new PricesDropdownData();
        $dropdownData['floors'] = new FloorsDropdownData();
        $dropdownData['area'] = new AreaDropdownData();
        $dropdownData['kitchen_area'] = new KitchenDropdownData();
        $dropdownData['finishing'] = new FinishingDropdownData($apartmentData);
        $dropdownData['bathroom_unit'] = new ToiletDropdownData($apartmentData);
        $dropdownData['mortgages'] = new MortgageDropdownData($apartmentData);
        $dropdownData['apartments'] = new ApartmentTypeDropdownData();
        $dropdownData['corpus'] = new CorpusDropdownData($apartmentData);

        $data['apartment_count'] = $apartmentData->count();
        $data['cheapest'] = $apartmentData->pluck('price')->min();
        $data['most_expensive'] = $apartmentData->pluck('price')->max();
        $data['smallest'] = $apartmentData->pluck('area')->min();
        $data['biggest'] = $apartmentData->pluck('area')->max();
        $data['dropdownData'] = $dropdownData;

        return $data;
    }

    public function formatMetaData()
    {
        $newH1 = "ЖК {$this->name}";
        $apartmentData = $this->apartments()->get();
        $cheapest = PriceTextFormatter::priceToText($apartmentData->pluck('price')->min(), '.');
        $cityCode = $this->location->code;
        $where = app()->get(CityService::class)->where[$cityCode];

        $newTitle = "Купить квартиру в {$newH1} в {$where} от застройщика, цены на квартиры, планировки";
        $newMetaDescription = "Купите квартиру в {$newH1} без комиссии и переплат. Продажа квартир в {$this->name} по цене от {$cheapest}";
        $updatedMeta = json_decode($this->meta, true);
        $hasDescription = false;

        if (! $updatedMeta) {
            $updatedMeta = [];
        } else {
            foreach ($updatedMeta as $metaUnit) {
                if ($metaUnit['name'] == 'description') {
                    $hasDescription = true;
                    $metaUnit['content'] = $newMetaDescription;
                }
            }
        }

        if (! $hasDescription) {
            $updatedMeta[] = ['name' => 'description', 'content' => $newMetaDescription];
        }

        $updatedData = [
            'h1' => $newH1,
            'head_title' => $newTitle,
            'meta' => json_encode(mb_convert_encoding($updatedMeta, 'UTF-8', 'UTF-8'))
        ];

        $this->update($updatedData);
    }

    public function getResidentialComplexClass(): string
    {
        $classNumber = 0;
        $classes = ['Стандарт', 'Комфорт', 'Бизнес', 'Элитный'];
        // $hasTerrace = $this->apartments()->where('balcony', 'LIKE', '%террасс%');

        $comfortConditions = [
            ['ceiling_height', '>=', 2.7],
            ['area', '>=', 28],
            ['kitchen_space', '>=', 10]
        ];
        $businessConditions = [
            ['ceiling_height', '>=', 2.8],
            ['area', '>=', 50],
            ['kitchen_space', '>=', 20]
        ];
        $eliteConditions = [
            ['ceiling_height', '>=', 3],
            ['area', '>=', 65],
            ['kitchen_space', '>=', 25]
        ];

        if ($this->apartments()->where($eliteConditions)->exists()) {
            $classNumber = 3;

            return $classes[$classNumber];
        }

        if ($this->apartments()->where($businessConditions)->exists()) {
            $classNumber = 2;

            return $classes[$classNumber];
        }

        if ($this->apartments()->where($comfortConditions)->exists()) {
            $classNumber = 1;
        }

        return $classes[$classNumber];
    }

    public function getSectionCount(): int
    {
        return $this->apartments()->pluck('building_section')->unique()->count();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_key', 'key');
    }

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartment::class, 'complex_key', 'key');
    }

    public function apartmentSpecifics(): HasMany
    {
        return $this->hasMany(ResidentialComplexApartmentSpecifics::class, 'building_id');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(Gallery::class, 'building_id');
    }

    public function spritePositions(): HasMany
    {
        return $this->hasMany(SpriteImagePosition::class, 'building_id');
    }

    public function docs(): HasMany
    {
        return $this->hasMany(Doc::class, 'complex_id');
    }

    public function amenities(): HasMany
    {
        return $this->hasMany(Amenity::class, 'complex_id');
    }

    public function buildingProcess(): HasMany
    {
        return $this->hasMany(BuildingProcess::class, 'complex_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ResidentialComplexCategory::class, 'residential_complex_category_pivots', 'complex_id', 'category_id');
    }
}
