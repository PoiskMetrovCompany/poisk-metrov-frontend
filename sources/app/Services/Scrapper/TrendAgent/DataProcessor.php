<?php

namespace App\Services\Scrapper\TrendAgent;

use App\Core\Abstracts\AbstractService;
use App\Models\Apartment;
use App\Models\Builder;
use App\Models\Building;
use App\Models\ResidentialComplex;
use App\Models\Location;
use App\Repositories\ApartmentRepository;
use App\Repositories\BuilderRepository;
use App\Repositories\ResidentialComplexRepository;
use App\Repositories\LocationRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class DataProcessor extends AbstractService
{
    public function __construct(
        protected ApartmentRepository $apartmentRepository,
        protected BuilderRepository $builderRepository,
        protected ResidentialComplexRepository $residentialComplexRepository,
        protected LocationRepository $locationRepository
    ) {}

    public function processApartmentsBatch(array $apartments, array $metadata): void
    {
        DB::beginTransaction();

        try {
            $processedCount = 0;

            foreach ($apartments as $apartmentData) {
                $this->processApartment($apartmentData, $metadata);
                $processedCount++;
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processBuildingsBatch(array $buildings, array $metadata): void
    {
        DB::beginTransaction();

        try {
            $processedCount = 0;

            foreach ($buildings as $buildingData) {
                $this->processBuilding($buildingData, $metadata);
                $processedCount++;
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processComplexesBatch(array $complexes, array $metadata): void
    {
        DB::beginTransaction();

        try {
            $processedCount = 0;

            foreach ($complexes as $complexData) {
                $this->processComplex($complexData, $metadata);
                $processedCount++;
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processBuildersBatch(array $builders, array $metadata): void
    {
        DB::beginTransaction();

        try {
            $processedCount = 0;

            foreach ($builders as $builderData) {
                $this->processBuilder($builderData, $metadata);
                $processedCount++;
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processLocationsBatch(array $locations, array $metadata): void
    {
        DB::beginTransaction();

        try {
            $processedCount = 0;

            foreach ($locations as $locationData) {
                $this->processLocation($locationData, $metadata);
                $processedCount++;
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function processApartment(array $apartmentData, array $metadata): void
    {
        try {
            $formattedData = $this->formatApartmentData($apartmentData, $metadata);

            $apartment = Apartment::where('key', $formattedData['key'])->first();
            if ($apartment) {
                $apartment->update($formattedData);
            } else {
                Apartment::create($formattedData);
            }

            $this->updateComplexBuilderFromApartment($apartmentData);

        } catch (Exception $e) {

            throw $e;
        }
    }

    private function processComplex(array $complexData, array $metadata): void
    {
        try {
            $formattedData = $this->formatComplexData($complexData, $metadata);

            ResidentialComplex::updateOrCreate(
                ['key' => $formattedData['key']],
                $formattedData
            );

        } catch (Exception $e) {

            throw $e;
        }
    }

    private function processBuilding(array $buildingData, array $metadata): void
    {
        try {
            $formattedData = $this->formatBuildingData($buildingData, $metadata);

            Building::updateOrCreate(
                ['key' => $formattedData['key']],
                $formattedData
            );

        } catch (Exception $e) {

            throw $e;
        }
    }

    private function processBuilder(array $builderData, array $metadata): void
    {
        try {
            $formattedData = $this->formatBuilderData($builderData, $metadata);

            Builder::updateOrCreate(
                ['key' => $formattedData['key']],
                $formattedData
            );

        } catch (Exception $e) {

            throw $e;
        }
    }

    private function processLocation(array $locationData, array $metadata): void
    {
        try {
            $formattedData = $this->formatLocationData($locationData, $metadata);

            Location::updateOrCreate(
                ['key' => $formattedData['key']],
                $formattedData
            );

        } catch (Exception $e) {

            throw $e;
        }
    }

    private function formatApartmentData(array $data, array $metadata): array
    {
        $complexKey = $this->findComplexKeyForApartment($data);
        $buildingKey = $this->findBuildingKeyForApartment($data);

        $metaData = [
            'original_data' => $data,
            'metadata' => $metadata,
            'complex_key_found' => $complexKey,
            'building_key_found' => $buildingKey
        ];

        return [
            'key' => $data['_id'],
            'offer_id' => $data['_id'],
            'complex_id' => null,
            'apartment_type' => 'Квартира',
            'renovation' => $data['finishing'] ?? null,
            'balcony' => !empty($data['area_balconies_total']),
            'bathroom_unit' => $data['wc_count'] ?? 1,
            'floor' => $data['floor'],
            'apartment_number' => $data['number'],
            'plan_URL' => is_array($data['plan']) ? $data['plan'][0] ?? null : $data['plan'],
            'ceiling_height' => $data['height'] ?? null,
            'room_count' => $data['room'],
            'price' => $data['price'],
            'area' => $data['area_total'],
            'living_space' => $data['area_rooms_total'] ?? null,
            'kitchen_space' => $data['area_kitchen'] ?? null,
            'floor_plan_url' => null,
            'windows_directions' => null,
            'meta' => json_encode($metaData),
            'feed_source' => 'TrendAgent',
            'head_title' => $this->generateHeadTitle($data),
            'h1' => $this->generateH1($data),
            'complex_key' => $complexKey,
            'building_key' => $buildingKey,
        ];
    }

    private function formatComplexData(array $data, array $metadata): array
    {
        $address = is_array($data['address']) ? implode(', ', $data['address']) : ($data['address'] ?? '');
        $coordinates = $data['geometry']['coordinates'] ?? [];
        $latitude = $coordinates[1] ?? null;
        $longitude = $coordinates[0] ?? null;
        
        $metroStation = null;
        $metroTime = null;
        $metroType = null;
        
        if (isset($data['subway']) && is_array($data['subway']) && count($data['subway']) > 0) {
            $metroStation = $data['subway'][0]['subway_name'] ?? $data['subway'][0]['subway_id'] ?? null;
            $metroTime = $data['subway'][0]['distance_time'] ?? null;
            $metroType = $data['subway'][0]['distance_type'] ?? null;
        }

        $locationKey = $this->findLocationKey($data['district'] ?? null);
        $builderName = $this->findBuilderNameForComplex($data);
        $infrastructure = $this->formatInfrastructure($data);

        return [
            'key' => $data['_id'],
            'code' => $data['_id'],
            'name' => $data['name'],
            'builder' => $builderName,
            'description' => $data['description'] ?? '',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location_id' => null,
            'location_key' => $locationKey,
            'address' => $address,
            'metro_station' => $metroStation,
            'metro_time' => $metroTime,
            'metro_type' => $metroType,
            'infrastructure' => $infrastructure,
            'parking' => $this->formatParking($data),
            'panorama' => null,
            'corpuses' => $this->formatCorpuses($data),
            'meta' => json_encode($data),
            'elevator' => null,
            'primary_material' => null,
            'floors' => null,
            'primary_ceiling_height' => null,
            'on_main_page' => false,
            'head_title' => $this->generateComplexHeadTitle($data),
            'h1' => $data['name'],
        ];
    }

    private function formatBuildingData(array $data, array $metadata): array
    {
        $coordinates = $data['geometry']['coordinates'] ?? [];
        $latitude = $coordinates[1] ?? null;
        $longitude = $coordinates[0] ?? null;
        
        $complexKey = $data['block_id'] ?? null;

        $buildingMaterials = null;
        if (isset($data['building_type'])) {
            if (is_array($data['building_type'])) {
                $buildingMaterials = $data['building_type']['name'] ?? $data['building_type'][0] ?? null;
            } else {
                $buildingMaterials = $data['building_type'];
            }
        }
        
        $buildingMaterials = is_scalar($buildingMaterials) ? $buildingMaterials : null;
        $latitude = is_scalar($latitude) ? $latitude : null;
        $longitude = is_scalar($longitude) ? $longitude : null;
        $complexKey = is_scalar($complexKey) ? $complexKey : null;

        return [
            'key' => $data['_id'],
            'complex_key' => $complexKey,
            'building_materials' => $buildingMaterials,
            'building_state' => null,
            'building_phase' => null,
            'building_section' => 'Корпус 1',
            'floors_total' => null,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'ready_quarter' => null,
            'built_year' => null,
        ];
    }

    private function findLocationKey(?string $districtId): string
    {
        if (!$districtId) {
            return '5983801cd07ed144bb7cca26';
        }

        $location = Location::where('key', $districtId)->first();
        if ($location) {
            return $location->key;
        }

        return '5983801cd07ed144bb7cca26';
    }

    private function findBuilderNameForComplex(array $complexData): string
    {
        $possibleKeys = [
            'builder',
            'builder_name',
            'construction'
        ];

        foreach ($possibleKeys as $key) {
            if (isset($complexData[$key]) && !empty($complexData[$key])) {
                return $complexData[$key];
            }
        }

        return '';
    }

    private function updateComplexBuilderFromApartment(array $apartmentData): void
    {
        $blockId = $apartmentData['block_id'] ?? null;
        $blockBuilderName = $apartmentData['block_builder_name'] ?? null;
        $blockSubwayName = $apartmentData['block_subway_name'] ?? null;
        $blockAddress = $apartmentData['block_address'] ?? null;

        if ($blockId) {
            $complex = ResidentialComplex::where('key', $blockId)->first();
            if ($complex) {
                $updated = false;
                
                if ($blockBuilderName && $complex->builder !== $blockBuilderName) {
                    $complex->builder = $blockBuilderName;
                    $updated = true;
                }
                
                if ($blockSubwayName && is_array($blockSubwayName) && count($blockSubwayName) > 0) {
                    $metroName = $blockSubwayName[0];
                    if ($metroName && $complex->metro_station !== $metroName) {
                        $complex->metro_station = $metroName;
                        $updated = true;
                    }
                }
                
                if ($blockAddress && $complex->address !== $blockAddress) {
                    $complex->address = $blockAddress;
                    $updated = true;
                }
                
                if ($updated) {
                    $complex->save();
                }
            }
        }
    }

    private function formatInfrastructure(array $data): string
    {
        $infrastructure = [];
        
        if (isset($data['amenities']) && is_array($data['amenities'])) {
            $infrastructure['amenities'] = $data['amenities'];
        }
        
        if (isset($data['services']) && is_array($data['services'])) {
            $infrastructure['services'] = $data['services'];
        }
        
        return json_encode($infrastructure);
    }

    private function formatParking(array $data): ?string
    {
        if (isset($data['parking']) && !empty($data['parking'])) {
            return is_array($data['parking']) ? implode(', ', $data['parking']) : $data['parking'];
        }
        
        return null;
    }

    private function formatCorpuses(array $data): int
    {
        if (isset($data['corpuses']) && is_numeric($data['corpuses'])) {
            return (int) $data['corpuses'];
        }
        
        if (isset($data['buildings']) && is_array($data['buildings'])) {
            return count($data['buildings']);
        }
        
        return 0;
    }

    private function findComplexKeyForApartment(array $apartmentData): ?string
    {
        $possibleKeys = [
            'block_id',
            'complex_id', 
            'complex',
            'block',
            'building_id'
        ];

        foreach ($possibleKeys as $key) {
            if (isset($apartmentData[$key]) && !empty($apartmentData[$key])) {
                $complexKey = $apartmentData[$key];
                
                $complex = ResidentialComplex::where('key', $complexKey)->first();
                if ($complex) {
                    return $complexKey;
                }
            }
        }

        $apartmentId = $apartmentData['_id'] ?? null;
        if ($apartmentId) {
            $complex = ResidentialComplex::where('key', 'LIKE', '%' . substr($apartmentId, 0, 8) . '%')->first();
            if ($complex) {
                return $complex->key;
            }
        }

        return null;
    }

    private function findBuildingKeyForApartment(array $apartmentData): ?string
    {
        // Сначала попробуем найти здание по прямому совпадению ключей
        $possibleKeys = [
            'building_id',
            'building',
            'block_id'
        ];

        foreach ($possibleKeys as $key) {
            if (isset($apartmentData[$key]) && !empty($apartmentData[$key])) {
                $buildingKey = $apartmentData[$key];
                
                $building = DB::table('buildings')->where('key', $buildingKey)->first();
                if ($building) {
                    return $buildingKey;
                }
            }
        }

        // Если не найдено по прямому совпадению, ищем через complex_key
        $complexKey = $this->findComplexKeyForApartment($apartmentData);
        if ($complexKey) {
            // Берем первое здание из комплекса
            $building = DB::table('buildings')->where('complex_key', $complexKey)->first();
            if ($building) {
                return $building->key;
            }
        }

        return null;
    }

    private function formatBuilderData(array $data, array $metadata): array
    {
        return [
            'key' => $data['_id'],
            'construction' => $data['name'],
            'builder' => $data['name'],
            'city' => $metadata['city'],
        ];
    }

    private function formatLocationData(array $data, array $metadata): array
    {
        return [
            'key' => $data['_id'],
            'country' => 'Россия',
            'region' => $data['region'] ?? $metadata['city'],
            'code' => $this->generateLocationCode($data),
            'capital' => $metadata['city'],
            'district' => $data['district'] ?? '',
            'locality' => $data['locality'] ?? '',
        ];
    }

    private function generateHeadTitle(array $apartment): string
    {
        $rooms = $apartment['room'] === 0 ? 'Студия' : $apartment['room'] . '-комнатная квартира';
        $area = $apartment['area_total'] . ' м²';
        $price = number_format($apartment['price'] / 1000000, 1, ',', ' ') . ' млн ₽';

        return "Продажа {$rooms} {$area} по цене {$price}";
    }

    private function generateH1(array $apartment): string
    {
        $rooms = $apartment['room'] === 0 ? 'Студия' : $apartment['room'] . '-комнатная квартира';
        $area = $apartment['area_total'] . ' м²';
        $floor = $apartment['floor'] . ' этаж';

        return "{$rooms} {$area}, {$floor}";
    }

    private function generateComplexHeadTitle(array $complex): string
    {
        return "ЖК {$complex['name']} - цены на квартиры, планировки, отзывы";
    }

    private function generateComplexCode(string $name): string
    {
        $cleanName = strtolower(str_replace([' ', '-', '.', ','], '_', $name));
        return substr($cleanName, 0, 50);
    }

    private function generateLocationCode(array $data): string
    {
        $parts = array_filter([
            $data['region'] ?? '',
            $data['district'] ?? '',
            $data['locality'] ?? ''
        ]);

        return strtolower(str_replace([' ', '-'], '_', implode('_', $parts)));
    }
}
