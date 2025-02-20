<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\TextFormatters\PriceTextFormatter;

class ApartmentHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $apartmentHistory = $this->apartmentHistory->sortBy('created_at');

        if ($apartmentHistory->count() > 0) {
            $currentPrice = $this->price;
            $predPrice = $apartmentHistory->first()->price;
            $priceDifference = $currentPrice - $predPrice;
            $changeIndicator = $priceDifference >= 0 ? '+ ' : '- ';

            if ($priceDifference == 0) {
                $differenceToDisplay = 'Цена не менялась';
            } else {
                $differenceToDisplay = $changeIndicator . PriceTextFormatter::priceToText(abs($priceDifference), ' ', ' ₽', 1);
            }
            $firstDate = $this->getFullDate($apartmentHistory->first()->created_at);
            $lastDate = $this->getFullDate(date('Y-m-d H:i:s'));
            $pricesWithMarks = [];
            $lastChanges = [];
            $today = $this->getShortDate(date('Y-m-d H:i:s'));

            foreach ($apartmentHistory as $i => $history) {
                if ($i == 0 || ($this->getShortDate($history->created_at) != $this->getShortDate($apartmentHistory->skip($i - 1)->first()->created_at))) {
                    $pricesWithMarks[] = ['price' => $history->price, 'date' => $this->getShortDate($history->created_at)];
                }
            }

            if (count($pricesWithMarks) && $pricesWithMarks[count($pricesWithMarks) - 1]['date'] != $today) {
                $pricesWithMarks[] = ['price' => $this->price, 'date' => $today];
            }

            if ($firstDate == $lastDate && count($pricesWithMarks) == 1) {
                $pricesWithMarks[] = ['price' => $this->price, 'date' => $today];
            }

            $historyLength = count($pricesWithMarks);
            $startHistory = $historyLength - 3;

            if ($historyLength < 5) {
                $startHistory = 1;
            }

            for ($i = $startHistory; $i < $historyLength; $i++) {
                $lastChanges[] = [
                    'change' => $this->getPriceChange($pricesWithMarks[$i]['price'], $pricesWithMarks[$i - 1]['price']),
                    'price' => PriceTextFormatter::priceToText($pricesWithMarks[$i]['price'], ' ', ' ₽', 1)
                ];
            }

            return [
                'priceDifference' => $differenceToDisplay,
                'priceDifferenceValue' => $priceDifference,
                'firstDate' => $firstDate,
                'lastDate' => $lastDate,
                'history' => $pricesWithMarks,
                'lastChanges' => $lastChanges
            ];
        } else
            return [
                'priceDifference' => '',
                'priceDifferenceValue' => 0,
                'firstDate' => '',
                'lastDate' => '',
                'history' => [],
                'lastChanges' => []
            ];
    }

    private function getShortDate(string $date)
    {
        $months = [
            '01' => 'январь',
            '02' => 'февраль',
            '03' => 'март',
            '04' => 'апрель',
            '05' => 'май',
            '06' => 'июнь',
            '07' => 'июль',
            '08' => 'август',
            '09' => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        ];

        $month = $months[date('m', strtotime($date))];
        return "$month";
    }

    private function getFullDate(string $date)
    {
        $months = [
            '01' => 'январь',
            '02' => 'февраль',
            '03' => 'март',
            '04' => 'апрель',
            '05' => 'май',
            '06' => 'июнь',
            '07' => 'июль',
            '08' => 'август',
            '09' => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        ];

        $year = date('Y', strtotime($date));
        $month = $months[date('m', strtotime($date))];
        return "$month $year";
    }

    private function getPriceChange($currPrice, $predPrice)
    {
        $difference = $currPrice - $predPrice;
        $indicator = $difference >= 0 ? '+ ' : '- ';

        if ($difference == 0) {
            return 'Цена не менялась';
        } else {
            return $indicator . PriceTextFormatter::priceToText(abs($difference), ' ', ' ₽', 1);
        }
    }
}
