<?php

namespace App\Services;

use App\Core\Services\CityServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Class CityService.
 */
class CityService extends AbstractService implements CityServiceInterface
{
    private array $invalidCodes = [null, 'null', 'not-found', 'not found'];
    public array $possibleCityCodes = [
        'st-petersburg',
        'novosibirsk',
        'black-sea',
        'crimea',
        'moscow',
        'chelyabinsk',
        'ekaterinburg',
        'kaliningrad',
        'voronezh',
        'far-east',
        'krasnodar',
        'thailand',
        'ufa',
        'kazan'
    ];
    public array $cityNames = [
        'Новосибирск',
        'Санкт-Петербург',
        'Сочи',
        'Крым',
        'Москва',
        'Челябинск',
        'Екатеринбург',
        'Калининград',
        'Воронеж',
        'Дальний Восток',
        'Краснодар',
        'Тайланд',
        'Уфа',
        'Казань'
    ];
    public array $cityCodes = [
        'novosibirsk' => 'Новосибирск',
        'st-petersburg' => 'Санкт-Петербург',
        'black-sea' => 'Сочи',
        'crimea' => 'Крым',
        'moscow' => 'Москва',
        'chelyabinsk' => 'Челябинск',
        'ekaterinburg' => 'Екатеринбург',
        'kaliningrad' => 'Калининград',
        'voronezh' => 'Воронеж',
        'far-east' => 'Дальний Восток',
        'krasnodar' => 'Краснодар',
        'thailand' => 'Тайланд',
        'ufa' => 'Уфа',
        'kazan' => 'Казань'
    ];
    public array $where = [
        'novosibirsk' => 'Новосибирске',
        'st-petersburg' => 'Санкт-Петербурге',
        'black-sea' => 'Сочи',
        'crimea' => 'Крыму',
        'moscow' => 'Москве',
        'chelyabinsk' => 'Челябинске',
        'ekaterinburg' => 'Екатеринбурге',
        'kaliningrad' => 'Калининграде',
        'voronezh' => 'Воронеже',
        'far-east' => 'Дальнем Востоке',
        'krasnodar' => 'Краснодаре',
        'thailand' => 'Тайланде',
        'ufa' => 'Уфе',
        'kazan' => 'Казани'
    ];
    public array $what = [
        'novosibirsk' => 'Новосибирска',
        'st-petersburg' => 'Санкт-Петербурга',
        'black-sea' => 'Сочи',
        'crimea' => 'Крыма',
        'moscow' => 'Москвы',
        'chelyabinsk' => 'Челябинска',
        'ekaterinburg' => 'Екатеринбурга',
        'kaliningrad' => 'Калининграда',
        'voronezh' => 'Воронежа',
        'far-east' => 'Дальнего Востока',
        'krasnodar' => 'Краснодара',
        'thailand' => 'Тайланда',
        'ufa' => 'Уфы',
        'kazan' => 'Казани'
    ];

    public const DEFAULT_CITY = 'st-petersburg';

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
