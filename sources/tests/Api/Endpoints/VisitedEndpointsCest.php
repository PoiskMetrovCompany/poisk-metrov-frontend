<?php

declare(strict_types=1);


namespace Tests\Api\Endpoints;

use Illuminate\Http\Response;
use Tests\Support\ApiTester;

final class VisitedEndpointsCest
{
    public function testVisited(ApiTester $I): void
    {
        $I->sendPost('visited/update', [
            'page' => 'real-estate',
            'code' => 'aeron',
        ]);
        $response = json_decode($I->grabResponse(), true);
        $I->seeResponseIsJson();
        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
    }
}
