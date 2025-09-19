<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Cities extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'title', 'slug'];

    /**
     * Получить застройщиков для этого города.
     */
    public function builders(): HasMany
    {
        return $this->hasMany(Builder::class, 'city', 'slug');
    }

    /**
     * Получить менеджеров для этого города.
     */
    public function managers(): HasMany
    {
        return $this->hasMany(Manager::class, 'city', 'slug');
    }

    /**
     * Получить фиды недвижимости для этого города.
     */
    public function realtyFeedEntries(): HasMany
    {
        return $this->hasMany(RealtyFeedEntry::class, 'city', 'slug');
    }

    /**
     * Получить ипотечные программы для этого города.
     */
    public function mortgageCities(): HasMany
    {
        return $this->hasMany(MortgageCity::class, 'city', 'slug');
    }

    /**
     * Получить пользователей CRM для этого города.
     */
    public function crmUsers(): HasMany
    {
        return $this->hasMany(User::class, 'crm_city', 'slug');
    }

    /**
     * Получить пары токен-лид CRM для этого города.
     */
    public function chatTokenCRMLeadPairs(): HasMany
    {
        return $this->hasMany(ChatTokenCRMLeadPair::class, 'crm_city', 'slug');
    }

    /**
     * Получить лучшие предложения для этого города.
     */
    public function bestOffers(): HasMany
    {
        return $this->hasMany(BestOffer::class, 'location_code', 'slug');
    }

    /**
     * Получить локации для этого города.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'code', 'slug');
    }

    /**
     * Получить жилые комплексы для этого города через локации.
     */
    public function residentialComplexes()
    {
        return $this->hasManyThrough(
            ResidentialComplex::class,
            Location::class,
            'code', // Foreign key on locations table
            'location_key', // Foreign key on residential_complexes table
            'slug', // Local key on cities table
            'key' // Local key on locations table
        );
    }

    /**
     * Получить квартиры для этого города через жилые комплексы и локации.
     */
    public function apartments()
    {
        return Apartment::whereHas('residentialComplex.location', function($query) {
            $query->where('code', $this->slug);
        });
    }
}
