<?php

declare(strict_types=1);


namespace Tests\Api\Endpoints;

use App\Models\AuthorizationCall;
use App\Repositories\AuthorizationCallRepository;
use Tests\Support\ApiTester;

final class AccountEndpointsCest
{
    public function getAuthentication(ApiTester $I): void
    {
        $I->sendPost('users/account/authentication', ['phone' => '+7 (993) 952-00-85']);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse(), true);
        $date = $response['attributes'];

        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);
    }

    public function getAuthorization(ApiTester $I): void
    {
        $phone = '+7 (993) 952-00-85';
        $pincode = 1234;
        $data = [
            'phone' => $phone,
            'pincode' => $pincode,
            'call_id' => rand(1, 1000000)
        ];

        $authorizationCallRepository = new AuthorizationCallRepository(new AuthorizationCall());
        $call = $authorizationCallRepository->findByPhone($phone);

        if ($call == null) {
            $authorizationCallRepository->store($data);
        } else {
            $call->update($data);
            $call->save();
        }
        $I->sendPost('users/account/authorization', [
            'phone' => '+7 (993) 952-00-85',
            'pincode' => "$pincode"
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse(), true);
        $date = $response['attributes'];

        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);

    }

    public function getLogout(ApiTester $I): void
    {
        // TODO: выполняется с токеном авторизации
//        $I->sendGet('users/account/logout');
//
//        $I->seeResponseCodeIs(200);
//        $I->seeResponseIsJson();
//
//        $response = json_decode($I->grabResponse(), true);
//        $date = $response['attributes'];
//
//        $I->assertArrayHasKey('identifier', $response);
//        $I->assertArrayHasKey('attributes', $response);
    }
}
