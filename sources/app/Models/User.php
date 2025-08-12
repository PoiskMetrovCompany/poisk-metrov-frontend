<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\CRM\Commands\UpdateLead;
use App\TextFormatters\PriceTextFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var array
     */
    const RELATIONSHIP = [
        'UserFavoriteBuilding' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'CRMSyncRequiredForUser' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'ResidentialComplexFeedSiteName' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'DeletedFavoriteBuilding' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'File' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'ManagerChatMessage' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'News' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'VisitedPage' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'UserFavoritePlan' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'Manager' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
        'Interaction' => ['main_table_value' => 'id', 'linked_table_value' => 'user_id'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'surname',
        'phone',
        'patronymic',
        'crm_id',
        'crm_city',
        'api_token',
        'chat_token',
        'is_test'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFullName(): string
    {
        $fullName = '';

        if ($this->name) {
            $fullName .= "{$this->name}";
        }

        if ($this->patronymic) {
            $fullName .= " {$this->patronymic}";
        }

        if ($this->surname) {
            $fullName .= " {$this->surname}";
        }

        return $fullName;
    }

    private function addBuildingList($title, $codes)
    {
        $newLines = [];
        $newLines[] = $title;
        $newLines[] = '';

        $buildings = ResidentialComplex::whereIn('code', $codes)->get();

        foreach ($buildings as $building) {
            $newLines[] = "- $building->name";
        }

        return $newLines;
    }

    private function addApartmentList($title, $offerIds)
    {
        $newLines = [];
        $newLines[] = $title;
        $newLines[] = '';

        $apartments = Apartment::whereIn('offer_id', $offerIds)->get();
        $buildingIds = $apartments->pluck('complex_id')->unique();
        $buildings = ResidentialComplex::whereIn('id', $buildingIds)->get();

        foreach ($buildings as $building) {
            $newLines[] = "- $building->name";
            $apartmentsInBuilding = $apartments->where('complex_id', $building->id)->sortBy('area')->sortBy('price');

            foreach ($apartmentsInBuilding as $favApartment) {
                $formattedPrice = PriceTextFormatter::priceToText($favApartment->price, '.');
                $newLines[] = "‌‌— {$favApartment->apartment_type} {$favApartment->room_count} к., {$favApartment->area} м², {$formattedPrice}";
            }

            $newLines[] = '';
        }

        return $newLines;
    }

    public function syncWithLead()
    {
        if ($this->crm_id == null) {
            return;
        }

        $favoriteBuildingCodes = $this->favoriteBuildings()->pluck('complex_code');
        $description = $this->addBuildingList('Избранные ЖК', $favoriteBuildingCodes);

        $favPlansOfferIds = $this->favoritePlans()->pluck('offer_id');
        $description[] = '';
        $description[] = '';
        $description = array_merge($description, $this->addApartmentList('Избранные квартиры', $favPlansOfferIds));

        $deletedFavoriteBuildingCodes = $this->deletedFavoriteBuildings()->pluck('complex_code');
        $description[] = '';
        $description[] = '';
        $description = array_merge($description, $this->addBuildingList('Удаленные из избранного ЖК', $deletedFavoriteBuildingCodes));

        $visitedRealEstatePages = $this->visitedPages()->where('page', 'real-estate')->get()->pluck('code');
        $description[] = '';
        $description[] = '';
        $description = array_merge($description, $this->addBuildingList('Посещенные страницы ЖК', $visitedRealEstatePages));

        $visitedPlanPageOfferIds = $this->visitedPages()->where('page', 'plan')->get()->pluck('code');
        $description[] = '';
        $description[] = '';
        $description = array_merge($description, $this->addApartmentList('Посещенные страницы квартир', $visitedPlanPageOfferIds));

        $description = implode("\r\n", $description);

        $comment = new \stdClass();
        $comment->input_id = 4;
        $comment->value = $description;
        $comment->value_type_id = 1;

        $fields = [$comment];

        $updateLead = new UpdateLead($this->crm_id, $fields, $this->crm_city);
        $updateLeadText = $updateLead->execute();
        $update = json_decode($updateLeadText);
    }

    public function connectWithManager()
    {
        $manager = Manager::where(['phone' => $this->phone])->first();

        if (! $manager) {
            return;
        }

        if (! isset($manager->user_id)) {
            $manager->update(['user_id' => $this->id]);
        }
    }

    public function favoritePlans(): HasMany
    {
        return $this->hasMany(UserFavoritePlan::class);
    }

    public function favoriteBuildings(): HasMany
    {
        return $this->hasMany(UserFavoriteBuilding::class);
    }

    public function deletedFavoriteBuildings(): HasMany
    {
        return $this->hasMany(DeletedFavoriteBuilding::class);
    }

    public function visitedPages(): HasMany
    {
        return $this->hasMany(VisitedPage::class);
    }

    static function createBearerToken($userAccount)
    {
        $userAccount->tokens()->delete();
        return $userAccount->createToken('user_account_token')->plainTextToken;
    }

    static function deleteBearerToken($userAccount)
    {
        return $userAccount->tokens()->delete();
    }
}
