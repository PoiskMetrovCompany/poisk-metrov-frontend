<?php

declare(strict_types=1);


namespace Tests\Unit\Entity;

use Illuminate\Support\Facades\Storage;
use Tests\Support\UnitTester;

final class CbrCest
{
    public function testFileExists(UnitTester $I)
    {
        $exists = \Storage::disk('local')->exists('cbr.json');
        $I->assertTrue($exists, 'Файл cbr.json не найден на диске local');
    }

    public function testFileContent(UnitTester $I)
    {
        $content = \Storage::disk('local')->get('cbr.json');
        $data = json_decode($content, true);

        $I->assertNotEmpty($content, 'Файл cbr.json пуст');
        $I->assertIsArray($data, 'Содержимое файла cbr.json не является валидным JSON');
        $I->assertNotEmpty($data, 'JSON в файле cbr.json пуст');
    }
}
