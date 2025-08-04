<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Interfaces\Services\GeoCodeServiceInterface;
use App\Core\Interfaces\Services\TextServiceInterface;
use Http;
use Illuminate\Support\Facades\Storage;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements GeoCodeServiceInterface
 * @property-read string $key
 * @property-read TextServiceInterface $textService
 */
final class GeoCodeService extends AbstractService implements GeoCodeServiceInterface
{
    protected string $key;

    public function __construct(protected TextServiceInterface $textService)
    {
        $data = Storage::json('yandex-geocoder-key.json');
        $this->key = $data['key'];
    }

    public function getGeoData(string $addressOrCoordinates): mixed
    {
        //coordinates format must be longitude/latitude
        $addressForSearch = $this->textService->unicodeToCyrillics(str_replace(' ', '+', $addressOrCoordinates));
        $url = "https://geocode-maps.yandex.ru/1.x/?apikey={$this->key}&geocode={$addressForSearch}&format=json";
        // echo $url . PHP_EOL;
        $response = Http::get($url);

        return json_decode($response);
    }

    public function geoDataHasFoundResults($geoData): bool
    {
        return $geoData->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0;
    }

    public function getFullLocationByCoordinates(array $coordinates): array|null
    {
        $geoData = $this->getGeoData(implode(',', $coordinates));

        if (! $this->geoDataHasFoundResults($geoData)) {
            return null;
        }

        $location = [];

        foreach ($geoData->response->GeoObjectCollection->featureMember as $featureMember) {
            $components = $featureMember->GeoObject->metaDataProperty->GeocoderMetaData->Address->Components;
            $kind = $featureMember->GeoObject->metaDataProperty->GeocoderMetaData->kind;

            foreach ($components as $component) {
                if ($kind == 'province') {
                    continue;
                }

                //Назначем первый попавшийся район, а потом стараемся его перезаписать
                if ($component->{'kind'} != 'district' || ! isset($location['district']) ||
                    (isset($location['district']) && str_contains($component->{'name'}, ' район'))) {

                    $location[$component->{'kind'}] = $this->textService->toUpper($this->textService->unicodeToCyrillics($component->{'name'}));
                }
            }
        }

        return $location;
    }

    public function getAddressByCoordinates(array $coordinates): string|null
    {
        $geoData = $this->getGeoData(implode(',', $coordinates));

        if (! $this->geoDataHasFoundResults($geoData)) {
            return null;
        }

        $address = $geoData->response->GeoObjectCollection->featureMember[0]->GeoObject->name;

        return $address;
    }

    //на ключ не более 1000 запросов в сутки
    public function getCoordsByAddress(string $address): array
    {
        $geoData = $this->getGeoData($address);

        if (! $this->geoDataHasFoundResults($geoData)) {
            return [0, 0];
        }

        $coords = $geoData->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        $coords = explode(' ', $coords);

        return $coords;
    }

    public static function getFromApp(): GeoCodeService
    {
        return parent::getFromApp();
    }
}
