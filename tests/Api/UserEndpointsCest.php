<?php

declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;

final class UserEndpointsCest
{
    public function getList(ApiTester $I): void
    {
        // TODO: выполняется с токеном авторизации
//        $I->sendGet('/users/list');
//        $this->assertResponseAndStructure($I);
    }

    public function getRelationshipList(ApiTester $I): void
    {
        // TODO: выполняется с токеном авторизации
//        $I->sendGet('/users/list?includes=UserFavoriteBuilding');
//        $this->assertResponseAndStructure($I, true);
    }

    public function updateRoleUser(ApiTester $I): void
    {
        // TODO: когда появятся задачи по ролям или админке то добавить
    }

    public function updateUser(ApiTester $I): void
    {
        $faker = \Faker\Factory::create('ru_RU');
        // TODO: выполняется с токеном авторизации
//        $user = [
//            'phone' => '+7 (' . $faker->numerify('###') . ') ' . $faker->numerify('###-##-##'),
//            'name' => $faker->name,
//            'surname' => $faker->firstName
//        ];
//
//        $I->sendPatch('/users/update', ['phone' => $user['phone']]);
//        $this->assertResponseAndStructure($I);
//        $this->assertModificationDateStructure($I, $user);

//        $I->sendPatch('/users/update', ['name' => $user['name']]);
//        $this->assertResponseAndStructure($I);
//        $this->assertModificationDateStructure($I, $user);
//
//        $I->sendPatch('/users/update', ['surname' => $user['surname']]);
//        $this->assertResponseAndStructure($I);
//        $this->assertModificationDateStructure($I, $user);
    }

    /**
     * @param ApiTester $I
     * @param bool $withIncludes
     */
    private function assertResponseAndStructure(ApiTester $I, bool $withIncludes = false): void
    {
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse(), true);
        $I->assertArrayHasKey('identifier', $response);
        $I->assertArrayHasKey('attributes', $response);

        $items = $response['attributes'];
        $I->assertIsArray($items);
        $I->assertCount(4, $items);

        foreach ($items as $item) {
            $this->assertUserStructure($I, $item);

            if ($withIncludes) {
                $this->assertIncludesStructure($I, $item);
            }
        }
    }

    /**
     * @param ApiTester $I
     * @param array $user
     */
    private function assertUserStructure(ApiTester $I, array $user): void
    {
        $requiredKeys = [
            'id', 'crm_id', 'crm_city', 'name', 'surname', 'email', 'phone',
            'role', 'patronymic', 'is_test', 'created_at', 'updated_at'
        ];

        foreach ($requiredKeys as $key) {
            $I->assertArrayHasKey($key, $user);
        }
    }

    /**
     * @param ApiTester $I
     * @param array $user
     */
    private function assertIncludesStructure(ApiTester $I, array $user): void
    {
        $I->assertArrayHasKey('includes', $user);

        foreach ($user['includes'] as $includeItem) {
            $I->assertArrayHasKey('type', $includeItem);
            $I->assertArrayHasKey('attributes', $includeItem);

            foreach ($includeItem['attributes'] as $attribute) {
                $requiredKeys = ['id', 'user_id', 'complex_code', 'created_at', 'updated_at'];
                foreach ($requiredKeys as $key) {
                    $I->assertArrayHasKey($key, $attribute);
                }
            }
        }
    }

    /**
     * @param ApiTester $I
     * @param array $user
     * @return void
     */
    private function assertModificationDateStructure(ApiTester $I, array $user): void
    {
//        $response = json_decode($I->grabResponse(), true);
//        $items = $response['attributes'];
//
//        $I->assertArrayHasKey('attributes', $response);
//        $I->assertIsArray($items);
//        $I->assertEquals($user, $items);
    }
}
