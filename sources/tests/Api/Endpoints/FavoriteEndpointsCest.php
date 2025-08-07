<?php

declare(strict_types=1);


namespace Tests\Api\Endpoints;

use Illuminate\Http\Response;
use Tests\Support\ApiTester;

final class FavoriteEndpointsCest
{
    public function testGetCountFavorites(ApiTester $I): void
    {
        $I->sendGET('favorites/count');
        $response = json_decode($I->grabResponse(), true);
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
    }

    /* TODO: выполняется с авторизацией
    public function testSwitchLikeFavorites(ApiTester $I): void
    {
        $I->sendPost('favorites/switch-like');
        $response = json_decode($I->grabResponse(), true);
        $I->seeResponseIsJson();
        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
    }
    */
}
