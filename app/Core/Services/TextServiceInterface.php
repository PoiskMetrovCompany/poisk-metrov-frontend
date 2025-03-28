<?php

namespace App\Core\Services;

interface TextServiceInterface
{
    /**
     * @param string $url
     * @return string
     */
    public function cleanupNmarketImageURL(string $url): string;

    /**
     * @param string $string
     */
    public function unicodeToCyrillics(string $string);

    /**
     * @param string $string
     * @return bool
     */
    public function isValidPercentNumber(string $string): bool;

    /**
     * @param string $string
     * @return string
     */
    public function transliterate(string $string): string;

    /**
     * @param string $url
     * @return string
     */
    public function removeQueryFromUrl(string $url): string;

    /**
     * @param string $string
     * @return string
     */
    public function toUpper(string $string): string;

    /**
     * @param string $string
     * @return string
     */
    public function getLastLinkPart(string $string): string;

    /**
     * @param string $string
     * @return string
     */
    public function getFileExtension(string $string): string;

    /**
     * @param string $str
     * @param string $insert
     * @param int $position
     * @return string
     */
    public function insertChars(string $str, string $insert, int $position): string;

    /**
     * @param string $value
     * @return string
     */
    public function removeExcelFormula(string $value): string;

    /**
     * @param string $phone
     * @return string
     */
    public function formatPhone(string $phone): string;

    /**
     * @param string $rawDate
     * @return string
     */
    public function formatDate(string $rawDate): string;
}
