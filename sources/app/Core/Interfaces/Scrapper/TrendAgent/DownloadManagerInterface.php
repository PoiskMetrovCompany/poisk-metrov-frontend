<?php

namespace App\Core\Interfaces\Scrapper\TrendAgent;

/**
 * @template TParser
 */
interface DownloadManagerInterface
{
    /**
     * @param string $url
     * @return array
     */
    public function downloadJson(string $url): array;

    /**
     * @param string $url
     * @return array|null
     */
    public function getCachedData(string $url): ?array;

    /**
     * @param string $url
     * @return bool
     */
    public function isDataFresh(string $url): bool;
}
