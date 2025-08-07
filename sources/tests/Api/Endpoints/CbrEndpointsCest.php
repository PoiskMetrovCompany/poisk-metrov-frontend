<?php

declare(strict_types=1);


namespace Tests\Api\Endpoints;

use Tests\Support\ApiTester;

final class CbrEndpointsCest
{
    public function getActualDate(ApiTester $I): void
    {
        $I->sendGET('/cbr/actual-date');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse(), true);
        $date = $response['attributes']['date'];

        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
        $I->assertArrayHasKey('date', $response['attributes']);
        $I->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $date, 'Неверный формат даты');
    }
}
