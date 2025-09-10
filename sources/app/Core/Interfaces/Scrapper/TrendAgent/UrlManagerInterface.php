<?php

namespace App\Core\Interfaces\Scrapper\TrendAgent;

/**
 * @template TParser
 */
interface UrlManagerInterface
{
    /**
     * @return array
     */
    public function getActiveUrls(): array;

    /**
     * @param string $url
     * @return void
     */
    public function markUrlAsProcessed(string $url): void;

    /**
     * @return array
     */
    public function getFailedUrls(): array;

    /**
     * @return void
     */
    public function retryFailedUrls(): void;
}
