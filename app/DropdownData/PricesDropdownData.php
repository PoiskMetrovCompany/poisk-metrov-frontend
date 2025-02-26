<?php

namespace App\DropdownData;

use App\TextFormatters\PriceTextFormatter;

class PricesDropdownData extends DropdownData
{
    public function __construct()
    {
        $startPrice = 2;
        $endPrice = 100;
        $values = [];
        $maxAvailablePrice = 30;
        $step = 0.5;

        for ($i = $startPrice; $i < $endPrice && $i <= $maxAvailablePrice; $i += $step) {
            $values[] = $i * 1000000;
            if ($i >= 10) {
                $step = 1;
            }
        }

        parent::__construct($values, '=', 'price');
        $this->ranged = true;

        foreach ($this->data as &$dataUnit) {
            $value = $dataUnit['value'];
            $dataUnit['displayName'] = PriceTextFormatter::priceToText($value, ' ', '', 1);
        }

        $this->inputPlaceholderFrom = 'Цена от, млн ₽';
        $this->inputPlaceholderTo = 'Цена до, млн ₽';
        $this->searchidFrom = 'price-from';
        $this->searchidTo = 'price-to';
    }
}