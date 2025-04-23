<?php

declare(strict_types=1);


namespace Tests\Api;

use Codeception\Util\HttpCode;
use Tests\Support\ApiTester;

final class ApartmentEndpointsCest
{
    public function getApartmentList(ApiTester $I): void
    {
        // TODO: Сделать фильтр на количество только для тестов
//        $I->sendGET('/apartments/list');
//        $I->seeResponseCodeIs(HttpCode::OK);
//        $I->seeResponseIsJson();

//        $response = json_decode($I->grabResponse(), true);
//
//        $I->assertArrayHasKey('identifier', $response);
//        $I->assertArrayHasKey('attributes', $response);
//
//        $items = $response['attributes'];
//        $I->assertIsArray($items);
//        $I->assertNotEmpty($items);
    }
}
