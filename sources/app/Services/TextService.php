<?php

namespace App\Services;

use App\Core\Abstracts\AbstractService;
use App\Core\Common\MonthConst;
use App\Core\Common\TextConst;
use App\Core\Interfaces\Services\TextServiceInterface;
use DateTime;
use Illuminate\Support\Collection;
use Str;

/**
 * @package App\Services
 * @extends AbstractService
 * @implements TextServiceInterface
 */
final class TextService extends AbstractService implements TextServiceInterface
{
    public function cleanupNmarketImageURL(string $url): string
    {
        if (Str::startsWith($url, 'http') && Str::contains($url, '/?v')) {
            $imageAndParameters = explode('/?v', $url);
            $url = $imageAndParameters[0];
        }

        return $url;
    }

    public function unicodeToCyrillics(string $string)
    {
        return transliterator_create('Hex-Any')->transliterate($string);
    }

    public function isValidPercentNumber(string $string): bool
    {
        $allowedSymbols = new Collection(TextConst::ALLOWED_SYMBOLS);

        foreach (str_split($string) as $symbol) {
            if (! $allowedSymbols->contains($symbol)) {
                return false;
            }
        }

        return true;
    }

    public function transliterate(string $string): string
    {
        $tempCode = transliterator_transliterate('Any-Latin;Latin-ASCII;', $string);
        $tempCode = str_split(strtolower($tempCode));
        $newCode = '';

        foreach ($tempCode as $char) {
            if (ctype_digit($char) || ctype_alpha($char) || $char == '-') {
                $newCode .= $char;
            }
        }

        return $newCode;
    }

    public function removeQueryFromUrl(string $url): string
    {
        if (! Str::contains($url, '?')) {
            return $url;
        }

        $split = explode('?', $url);

        return $split[0];
    }

    public function toUpper(string $string): string
    {
        if (strlen($string) == 0) {
            return '';
        }

        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }

    public function getLastLinkPart(string $string): string
    {
        $lastPart = explode('/', $string);

        return $lastPart[count($lastPart) - 1];
    }

    public function getFileExtension(string $string): string
    {
        $fileName = $this->getLastLinkPart($string);
        $split = explode('.', $fileName);

        return $split[count($split) - 1];
    }

    public function insertChars(string $str, string $insert, int $position): string
    {
        return substr($str, 0, $position) . $insert . substr($str, $position);
    }

    public function removeExcelFormula(string $value): string
    {
        $value = explode(',', $value);
        $value = $value[count($value) - 1];
        $value = str_replace(['"', ')'], '', $value);

        return $value;
    }

    public function formatPhone(string $phone): string
    {
        $phone = str_replace(' ', '', strval($phone));

        while (strlen($phone) <= 10) {
            $phone .= '0';
        }

        if (substr($phone, 0, 1) != '+') {
            $phone = "+$phone";
        }

        if (substr($phone, 1, 1) == '8') {
            $phone[1] = '7';
        }

        $phone = $this->insertChars($phone, ' (', 2);
        $phone = $this->insertChars($phone, ') ', 7);
        $phone = $this->insertChars($phone, '-', 12);
        $phone = $this->insertChars($phone, '-', 15);

        return $phone;
    }

    public function formatDate(string $rawDate): string
    {
        $months = MonthConst::MONTHS;
        $date = new DateTime($rawDate);
        $day = $date->format('d');
        $monthNumber = $date->format('m');
        $year = $date->format('Y');
        return "{$day} {$months[$monthNumber]} {$year}";
    }

    public static function getFromApp(): TextService
    {
        return parent::getFromApp();
    }
}
