<?php

namespace App\Models;

use App\TextFormatters\PriceTextFormatter;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Apartment extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'ResidentialComplex' => ['main_table_value' => 'complex_key', 'linked_table_value' => 'key'],
        'ApartmentHistory' => ['main_table_value' => 'id', 'linked_table_value' => 'apartment_id'],
        'Interaction' => ['main_table_value' => 'id', 'linked_table_value' => 'apartment_id'],
        'MortgageType' => ['main_table_value' => 'id', 'linked_table_value' => 'apartment_id'],
        'Renovation' => ['main_table_value' => 'id', 'linked_table_value' => 'offer_id'],
        'UserFavoritePlan' => ['main_table_value' => 'id', 'linked_table_value' => 'offer_id'],
        'Doc' => ['main_table_value' => 'complex_key', 'linked_table_value' => 'complex_key'],
        'Building' => [
            'model' => \App\Models\Building::class,
            'main_table_value' => 'complex_key',
            'linked_table_value' => 'complex_key',
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'offer_id',
        'complex_id',
        'apartment_type',
        'renovation',
        'balcony',
        'bathroom_unit',
        'floor',
        'floors_total',
        'apartment_number',
        'building_materials',
        'building_state',
        'building_phase',
        'building_section',
        'latitude',
        'longitude',
        'ready_quarter',
        'built_year',
        'plan_URL',
        'ceiling_height',
        'room_count',
        'price',
        'area',
        'living_space',
        'kitchen_space',
        'floor_plan_url',
        'windows_directions',
        'meta',
        'head_title',
        'h1',
        'feed_source',
        'complex_key',
        'building_key'
    ];

    public static $searchableFields = [
        'apartment_type',
        'renovation',
        'bathroom_unit',
        'built_year',
        'room_count',
        'mortgage',
        'floor-from',
        'price-from',
        'area-from',
        'kitchen_space-from',
        'floor-to',
        'price-to',
        'area-to',
        'kitchen_space-to',
        'apartment_type-not',
        'built_year-to',
        'built_year-from',
        'building_section',
        'building_materials'
    ];

    public function isFavorite()
    {
        if (key_exists('favoritePlans', $_COOKIE)) {
            $split = explode(',', $_COOKIE['favoritePlans']);

            if (in_array($this->offer_id, $split)) {
                return true;
            }
        }

        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return UserFavoritePlan::where(['user_id' => $user->id, 'offer_id' => $this->offer_id])->exists();
    }

    public function formatMetaData()
    {
        $parentComplex = $this->residentialComplex()->get()->first();
        $complexName = $parentComplex->h1;

        $shortDescription = trim($parentComplex->description);
        $shortDescription = explode('. ', $shortDescription);
        $shortDescription = array_slice($shortDescription, 0, 2);
        $shortDescription = implode('. ', $shortDescription) . '.';
        $address = $parentComplex->address;

        $newH1 = "{$this->room_count}-комнатная квартира в {$complexName}, {$this->area} м², этаж {$this->floor}";
        $formattedPrice = PriceTextFormatter::priceToText($this->price, '.');
        $newTitle = "Продажа {$this->room_count}-комнатной квартиры {$this->area} м² по цене {$formattedPrice} по адресу: {$address}";
        $newMetaDescription = "Продажа {$this->room_count}-комнатной квартиры {$this->area} м² по цене {$formattedPrice}. {$shortDescription}";

        if ($this->apartment_type == 'Студия') {
            $newH1 = "Квартира-студия в {$complexName}, {$this->area} м², этаж {$this->floor}";
            $newTitle = "Продажа квартиры-студии {$this->area} м² по цене {$formattedPrice} по адресу: {$address}";
            $newMetaDescription = "Продажа квартиры-студии {$this->area} м² по цене {$formattedPrice}. {$shortDescription}";
        }

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

    public function residentialComplex(): BelongsTo
    {
        return $this->belongsTo(ResidentialComplex::class, 'complex_id', 'id');
    }

    public function residentialComplexByKey(): BelongsTo
    {
        return $this->belongsTo(ResidentialComplex::class, 'complex_key', 'key');
    }

    public function renovationUrl(): HasMany
    {
        return $this->hasMany(Renovation::class, 'offer_id', 'offer_id');
    }

    public function mortgageTypes(): HasMany
    {
        return $this->hasMany(MortgageType::class, 'apartment_id');
    }

    public function apartmentHistory(): HasMany
    {
        return $this->hasMany(ApartmentHistory::class, 'apartment_id');
    }
}
