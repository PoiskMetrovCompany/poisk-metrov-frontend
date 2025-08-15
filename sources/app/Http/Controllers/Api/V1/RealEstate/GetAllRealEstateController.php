<?php

namespace App\Http\Controllers\Api\V1\RealEstate;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\ApartmentRepositoryInterface;
use App\Core\Interfaces\Repositories\LocationRepositoryInterface;
use App\Core\Interfaces\Repositories\ResidentialComplexRepositoryInterface;
use App\Http\Resources\RealEstate\NavigateRealEstateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class GetAllRealEstateController extends AbstractOperations
{
    public function __construct(
        protected ResidentialComplexRepositoryInterface $residentialComplexRepository,
        protected ApartmentRepositoryInterface  $apartmentRepository,
        protected LocationRepositoryInterface  $locationRepository,

    )
    {
    }

    /**
     * @OA\Get(
     *      tags={"RealEstate"},
     *      path="/api/v1/real-estate/",
     *      summary="Фильтр недвижимости",
     *      description="Возвращение JSON объекта",
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *     @OA\Parameter(
     *           name="city",
     *           in="query",
     *           required=true,
     *           description="Имя города",
     *           @OA\Schema(type="string", example="novosibirsk")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $apartmentsCount = 0;
        $city = $request->input('city');
        $locale = $this->locationRepository->find(['code' => $city])->first();
        $residentialComplexes = $this->residentialComplexRepository->find(['location_key' => $locale->key])->get();

        foreach ($residentialComplexes as $residentialComplex) {
            $apartments = array_merge(
                $apartments,
                $this->apartmentRepository->find(['residential_complex_key' => $residentialComplex->key])->get()->toArray()
            );
        }

        $residentialComplexCount = count($residentialComplexes);
        $apartmentsCount = count($apartments);
        // TODO: добавить "Апартаменты" и "Дома"
        $dataOutput = [
            [
                'type' => 'ЖК',
                'navigate' => new NavigateRealEstateResource(
                    [
                        ['title' => 'Все проекты', 'value' => $residentialComplexCount],
                        ['title' => 'Популярные', 'value' => $residentialComplexCount],
                        ['title' => 'Акции', 'value' => $residentialComplexCount]
                    ]
                ),
                'attributes' => [
                    ['title' => 'ЖК у воды', 'proposal' => 11548, 'image' => '/icon/location.svg'],
                    ['title' => 'ЖК в центре', 'proposal' => 11548, 'image' => '/icon/building.svg'],
                    ['title' => 'ЖК с видом', 'proposal' => 11548, 'image' => '/icon/light.svg'],
                    ['title' => 'ЖК бизнес-класса', 'proposal' => 11548, 'image' => '/icon/building2.svg'],
                    ['title' => 'ЖК с отделкой', 'proposal' => 11548, 'image' => '/icon/send.svg'],
                    ['title' => 'ЖК с парками', 'proposal' => 11548, 'image' => '/icon/leaf.svg'],
                ]
            ],
            [
                'type' => 'Квартиры',
                'navigate' => new NavigateRealEstateResource(
                    [
                        ['title' => 'Все проекты', 'value' => $apartmentsCount],
                        ['title' => 'Популярные', 'value' => $apartmentsCount],
                        ['title' => 'Акции', 'value' => $apartmentsCount]
                    ]
                ),
                'attributes' => [
                    ['title' => '1-комнатные', 'proposal' => 11548, 'image' => '/icon/apartment1.svg'],
                    ['title' => '2-комнатные', 'proposal' => 11548, 'image' => '/icon/apartment2.svg'],
                    ['title' => '3-комнатные', 'proposal' => 11548, 'image' => '/icon/apartment3.svg'],
                    ['title' => 'Квартиры с террасой', 'proposal' => 11548, 'image' => '/icon/umbrella.svg'],
                    ['title' => 'Квартиры с отделкой', 'proposal' => 11548, 'image' => '/icon/send.svg'],
                    ['title' => 'Пенхаусы', 'proposal' => 11548, 'image' => '/icon/home.svg'],
                ]
            ]
        ];


        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($dataOutput),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return 'MixedResidentialComplex';
    }

    public function getResourceClass(): string
    {
        return 'MixedResidentialComplexResource';
    }

}
