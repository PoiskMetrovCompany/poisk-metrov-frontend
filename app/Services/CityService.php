<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Common\Cities\CityCodesConst;
use App\Core\Common\Cities\CityConst;
use App\Core\Common\Cities\CityInvalidCodeConst;
use App\Core\Common\Cities\CityNameListConst;
use App\Core\Interfaces\Services\CityServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements CityServiceInterface
 * @property-read array $invalidCodes
 * @property-read array $possibleCityCodes
 * @property-read array $cityNames
 * @property-read array $cityCodes
 * @property-read array $where
 * @property-read array $what
 */
final class CityService extends AbstractService implements CityServiceInterface
{
    private array $invalidCodes = CityInvalidCodeConst::NOT_VALID_CODES;
    public array $possibleCityCodes = CityCodesConst::CITY_CODES;
    public array $cityNames = CityNameListConst::CITY_NAME_LIST;
    public array $cityCodes = CityConst::CITY_CODES;
    public array $where = CityConst::WHERE;
    public array $what = CityConst::WHAT;

    public const DEFAULT_CITY = CityConst::DEFAULT_CITY;

    public function getUserCityName(): string
    {
        return $this->cityCodes[$this->getUserCity()];
    }

    public function getSortedCityNamesAndCodes(): Collection
    {
        return (new Collection($this->cityCodes))->sort()->flip();
    }

    public function getUserCity(): array|string
    {
        $selectedCity = CityService::DEFAULT_CITY;

        //Если есть город, то проверяем не битые ли куки и если нет,
        if (key_exists('selectedCity', $_COOKIE)) {
            $selectedCity = strtolower($_COOKIE['selectedCity']);

            if (in_array($selectedCity, $this->possibleCityCodes)) {
                return $selectedCity;
            }
        }

        if (! config('app.use_geolocation') || ! key_exists('REMOTE_ADDR', $_SERVER)) {
            $selectedCity = CityService::DEFAULT_CITY;

            $this->setCityCookie($selectedCity);

            return $selectedCity;
        }

        $userIP = $_SERVER['REMOTE_ADDR'];
        $address = "https://geolocation-db.com/json/{$userIP}";
        $request = Http::get($address);

        if ($request->successful()) {
            $geolocationData = $request->json();

            if ($geolocationData['city'] != null) {
                $geolocationData['city'] = strtolower($geolocationData['city']);
            }

            if (
                ! in_array($geolocationData['city'], $this->invalidCodes) &&
                in_array($geolocationData['city'], $this->cityCodes)
            ) {
                $selectedCity = str_replace(' ', '-', $geolocationData['city']);
            } else {
                $selectedCity = CityService::DEFAULT_CITY;
            }
        } else {
            $selectedCity = CityService::DEFAULT_CITY;
        }

        $this->setCityCookie($selectedCity);

        return $selectedCity;
    }

    public function setCityCookie(mixed $newCity): bool
    {
        if (in_array($newCity, $this->possibleCityCodes)) {
            setrawcookie('selectedCity', $newCity, time() + 31536000, '/');
            return true;
        }

        return false;
    }

    public function setCityFromURL(string $newCity)
    {
        $oldCity = $this->getUserCity();

        if ($this->setCityCookie($newCity)) {
            $url = url()->previous();
            $url = str_replace($oldCity, $newCity, $url);

            return redirect($url, 303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        } else {
            return redirect()->back(303)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }
    }

    public function getCitiesOtherThan(string $city)
    {
        return array_values(array_diff($this->possibleCityCodes, [$city]));
    }

    public static function getFromApp(): CityService
    {
        return parent::getFromApp();
    }
}
