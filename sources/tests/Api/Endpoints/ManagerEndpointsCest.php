<?php

declare(strict_types=1);


namespace Tests\Api\Endpoints;

use Illuminate\Http\Response;
use Tests\Support\ApiTester;

final class ManagerEndpointsCest
{
    public function testListManager(ApiTester $I): void
    {
        $I->sendGET('managers/list');
        $response = json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
        $I->assertNotEmpty($response['attributes']);
    }
}
