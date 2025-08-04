<?php

declare(strict_types=1);


namespace Tests\Api\Endpoints;

use Illuminate\Http\Response;
use Tests\Support\ApiTester;

final class FeedEndpointsCest
{
    public function testGetNameFeed(ApiTester $I): void
    {
        $this->seeGeneralTemplate(I: $I, url: '/feeds/get-name');
    }

    public function testReadFeed(ApiTester $I): void
    {
        $this->seeGeneralTemplate(I: $I, url: '/feeds/read');
    }

    public function testStoreFeed(ApiTester $I): void
    {
        $I->sendPost('/feeds/create', [
            'url' => 'http://example.com',
            'format' => 'avito',
            'city' => 'novosibirsk',
            'fallback_residential_complex_name' => 'Характер',
            'default_builder' => 'УК Малахит',
        ]);
        $response = json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(Response::HTTP_CREATED);
        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
    }

    public function testUpdateFeed(ApiTester $I): void
    {
        $I->sendPost('/feeds/update', [
            'id' => 1,
            'url' => 'http://example2.com',
            'format' => 'avito',
            'city' => 'novosibirsk',
            'fallback_residential_complex_name' => 'Характер2',
            'default_builder' => 'УК Малахит2',
        ]);
        $response = json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(Response::HTTP_CREATED);
        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
    }

    private function seeGeneralTemplate(ApiTester $I, string $url): ApiTester
    {
        $I->sendGET($url);
        $response = json_decode($I->grabResponse(), true);

        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseIsJson();
        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
        $I->assertNotEmpty($response['attributes']);
        return $I;
    }
}
