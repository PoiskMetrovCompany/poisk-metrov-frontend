<?php

namespace App\Core\Services;

interface PriceFormattingServiceInterface
{
    /**
     * @param int|null $priceAsNumber
     * @param string $delimiter
     */
    public function fullPrice(int|null $priceAsNumber, string $delimiter = ' ');

    /**
     * @param int|null $priceAsNumber
     * @param string $delimiter
     * @param string $postfix
     * @param int $divideBy
     * @param int $cutoff
     */
    public function priceToText(int|null $priceAsNumber, string $delimiter = '.', string $postfix = ' млн ₽', int $divideBy = 1000, int $cutoff = 0);
}
