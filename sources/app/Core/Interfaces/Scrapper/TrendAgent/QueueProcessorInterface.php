<?php

namespace App\Core\Interfaces\Scrapper\TrendAgent;

/**
 * @template TParser
 */
interface QueueProcessorInterface
{
    /**
     * @param array $data
     * @param string $type
     * @param array $metadata
     * @return void
     */
    public function addToQueue(array $data, string $type, array $metadata = []): void;

    /**
     * @param string $type
     * @return void
     */
    public function processQueue(string $type): void;

    /**
     * @return array
     */
    public function getQueueStatus(): array;
}
