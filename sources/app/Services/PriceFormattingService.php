<?php

namespace App\Services;

use App\Core\Interfaces\Services\PriceFormattingServiceInterface;

/**
 * @package App\Services
 * @implements PriceFormattingServiceInterface
 */
final class PriceFormattingService implements PriceFormattingServiceInterface
{
    public function fullPrice(int|null $priceAsNumber, string $delimiter = ' ')
    {
        return $this->priceToText($priceAsNumber, $delimiter, ' ₽', 1);
    }

    public function priceToText(int|null $priceAsNumber, string $delimiter = '.', string $postfix = ' млн ₽', int $divideBy = 1000, int $cutoff = 0)
    {
        if ($priceAsNumber == null) {
            return "";
        }

        $price = (string) ((int) ($priceAsNumber / $divideBy));
        $length = strlen($price);
        $offset = $length % 3 - 1;

        if ($offset == -1) {
            $offset = 2;
        }

        $splitPrice = str_split($price);
        $preparedPrice = '';
        $i = 0;

        foreach ($splitPrice as $char) {
            $preparedPrice .= $char;

            if ($i % 3 == $offset && $i != $length - 1) {
                $preparedPrice .= $delimiter;
            }

            $i++;
        }

        if ($cutoff > 0) {
            $preparedPrice = substr($preparedPrice, 0, strlen($preparedPrice) - $cutoff);
            $preparedPrice = trim($preparedPrice, $delimiter);
            $preparedPrice = trim($preparedPrice);
        }

        return "{$preparedPrice}{$postfix}";
    }
}
