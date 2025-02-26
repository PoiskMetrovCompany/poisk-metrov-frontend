<?php

namespace App\TextFormatters;

use App\Services\PriceFormattingService;
use Log;

class PriceTextFormatter
{
    public static function priceToText(int|null $priceAsNumber, string $delimiter = '.', string $postfix = ' млн ₽', int $divideBy = 1000, int $cutoff = 0)
    {
        return app()->get(PriceFormattingService::class)->priceToText($priceAsNumber, $delimiter, $postfix, $divideBy, $cutoff);
    }
}