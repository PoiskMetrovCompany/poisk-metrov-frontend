<?php

namespace App\Services;

use App\Core\Interfaces\Services\YandexSearchServiceInterface;
use Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements YandexSearchServiceInterface
 * @property-read array $keys
 */
final class YandexSearchService extends AbstractService implements YandexSearchServiceInterface
{
    private array $keys = [];

    public function __construct()
    {
        $keysJson = Storage::json('yandex-search-key.json');
        $this->keys = $keysJson['keys'];
    }

    public function getBusinesses(string $requestText, float $longitude, float $latitude, string $type = 'biz', string $spn1 = '0.100000', string $spn2 = '0.100000', int $max = 1, int $keyNumber = 0): string
    {
        $parameters = [
            'type' => $type,
            'll' => "{$longitude},{$latitude}",
            'spn' => "{$spn1},{$spn2}",
            'results' => $max
        ];

        return $this->getResultsByName($requestText, $parameters, $keyNumber);
    }

    public function getResultsByName(string $requestText, array $additionalParameters = [], int $keyNumber = 0): string
    {
        $URL = 'https://search-maps.yandex.ru/v1/';
        $lang = 'ru_RU';
        $key = $this->keys[$keyNumber];
        $fullURL = "{$URL}?text={$requestText}&lang={$lang}&apikey={$key}";

        foreach ($additionalParameters as $key => $value) {
            $fullURL .= "&{$key}={$value}";
        }

        // echo $fullURL.PHP_EOL;

        return Http::get($fullURL)->body();
    }

    public static function getFromApp(): YandexSearchService
    {
        return app()->get(get_called_class());
    }

}
