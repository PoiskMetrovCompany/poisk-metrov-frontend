<?php

namespace App\Services\Filter;

use App\Core\Abstracts\AbstractFilter;
use App\Core\Common\RootEntityEnum;
use App\Core\DTO\CatalogFilterDTO;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\CityRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Core\Interfaces\Services\FilterServiceInterface;
use App\Services\Filter\Commands\AreaFilterCommand;
use App\Services\Filter\Commands\CeilingHeightFilterCommand;
use App\Services\Filter\Commands\ElevatorFilterCommand;
use App\Services\Filter\Commands\FloorFilterCommand;
use App\Services\Filter\Commands\LivingAreaFilterCommand;
use App\Services\Filter\Commands\MetroFilterCommand;
use App\Services\Filter\Commands\ParkingFilterCommand;
use App\Services\Filter\Commands\PriceFilterCommand;
use App\Services\Filter\Commands\RenovationFilterCommand;
use App\Services\Filter\Commands\RoomCountFilterCommand;
use App\Services\Filter\Commands\ApartmentTypeFilterCommand;
use App\Services\Filter\Commands\BathroomFilterCommand;
use App\Services\Filter\Commands\DeveloperFilterCommand;
use App\Services\Filter\Commands\FloorCountsFilterCommand;
use App\Services\Filter\Commands\MontageTypeFilterCommand;
use App\Services\Filter\Commands\SearchFilterCommand;
use App\Services\Filter\Commands\CityFilterCommand;
use App\Services\Filter\Traits\GetAreaTotalTrait;
use App\Services\Filter\Traits\GetCeilingHeightTrait;
use App\Services\Filter\Traits\GetCountRoomsTrait;
use App\Services\Filter\Traits\GetFloorTrait;
use App\Services\Filter\Traits\GetLivingAreaTrait;
use App\Services\Filter\Traits\GetPricingTrait;
use Illuminate\Database\Eloquent\Builder;

final class FilterService extends AbstractFilter implements FilterServiceInterface
{
    use GetAreaTotalTrait;
    use GetCeilingHeightTrait;
    use GetCountRoomsTrait;
    use GetFloorTrait;
    use GetLivingAreaTrait;
    use GetPricingTrait;

    private CommandInvoker $commandInvoker;

    public function __construct(
        protected CityRepositoryInterface $cityRepository,
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected ApartmentRepositoryInterface $apartmentRepository,
    )
    {
        $this->commandInvoker = new CommandInvoker();
        $this->initializeCommands();
    }

    private function initializeCommands(): void
    {
        $this->commandInvoker
            // Сначала обязательный фильтр города
            ->addCommand(new CityFilterCommand())
            ->addCommand(new PriceFilterCommand())
            ->addCommand(new RoomCountFilterCommand())
            ->addCommand(new AreaFilterCommand())
            ->addCommand(new FloorFilterCommand())
            ->addCommand(new LivingAreaFilterCommand())
            ->addCommand(new CeilingHeightFilterCommand())
            ->addCommand(new RenovationFilterCommand())
            ->addCommand(new ParkingFilterCommand())
            ->addCommand(new ElevatorFilterCommand())
            ->addCommand(new MetroFilterCommand())
            ->addCommand(new ApartmentTypeFilterCommand())
            ->addCommand(new BathroomFilterCommand())
            ->addCommand(new DeveloperFilterCommand())
            ->addCommand(new FloorCountsFilterCommand())
            ->addCommand(new MontageTypeFilterCommand())
            ->addCommand(new SearchFilterCommand());
    }

    final public function entityBuild(string $entityType): ResidentialComplexRepositoryInterface | ApartmentRepositoryInterface
    {
        return $entityType === RootEntityEnum::RESIDENTIAL_COMPLEX->value
            ? $this->residentialComplexRepository
            : $this->apartmentRepository;
    }

    /**
     * @param CatalogFilterDTO $attributes
     * @return array
     */
    public function execute(CatalogFilterDTO $attributes): array
    {
        $repository = $this->entityBuild($attributes->entityType);
        $query = $repository->find([]);
        $totalCount = $query->count();
        $query = $this->optimizeQuery($query, $attributes->entityType);
        $filterValues = $this->convertDtoToFilterArray($attributes);
        $filteredQuery = $this->commandInvoker->executeChain($query, $filterValues);
        $filteredCount = $filteredQuery->count();
        $results = $filteredQuery->paginate(20);

        $response = [
            'attributes' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total(),
            ],
        ];


        return $response;
    }

    /**
     * @param Builder $query
     * @param string $entityType
     * @return Builder
     */
    private function optimizeQuery(Builder $query, string $entityType): Builder
    {
        if ($entityType === 'Квартиры') {
            return $query->with(['residentialComplex' => function ($query) {
                $query->select(['id', 'key', 'name', 'parking', 'elevator', 'metro_station', 'metro_time']);
            }])
            ->select([
                'id', 'key', 'complex_id', 'complex_key', 'apartment_number', 'floor',
                'room_count', 'price', 'area', 'living_space', 'ceiling_height',
                'renovation', 'balcony', 'bathroom_unit', 'created_at', 'updated_at'
            ]);
        } else {
            return $query->select([
                'id', 'key', 'name', 'code', 'address', 'latitude', 'longitude',
                'parking', 'elevator', 'floors', 'primary_ceiling_height',
                'metro_station', 'metro_time', 'created_at', 'updated_at'
            ]);
        }
    }

    /**
     * @param CatalogFilterDTO $dto
     * @return array
     */
    private function convertDtoToFilterArray(CatalogFilterDTO $dto): array
    {
        return [
            'city' => $dto->city,
            'price' => $dto->pricing,
            'room_count' => $dto->countRooms,
            'area' => $dto->areaTotal,
            'floor' => $dto->floors,
            'living_area' => $dto->livingArea,
            'ceiling_height' => $dto->ceilingHeight,
            'renovation' => $dto->finishing,
            'parking' => $dto->parking,
            'elevator' => $dto->elevator,
            'metro' => $dto->toMetro,
            'apartment_type' => $dto->apartments,
            'bathroom' => $dto->bathroom,
            'developer' => $dto->developer,
            'floor_counts' => $dto->floorCounts,
            'montage_type' => $dto->montageType,
            'search' => $dto->search,
        ];
    }

    /**
     * @param array $filterValues
     * @return array
     */
    private function getAppliedFilters(array $filterValues): array
    {
        $applied = [];
        foreach ($filterValues as $key => $value) {
            if (!is_null($value) && $value !== '' && $value !== []) {
                $applied[] = $key;
            }
        }
        return $applied;
    }
}
