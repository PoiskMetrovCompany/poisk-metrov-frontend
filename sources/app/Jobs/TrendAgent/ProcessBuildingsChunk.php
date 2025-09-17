<?php

namespace App\Jobs\TrendAgent;

use App\Services\Scrapper\TrendAgent\DataProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProcessBuildingsChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $buildings,
        public array $metadata
    ) {}

    public function handle(DataProcessor $dataProcessor): void
    {
        $dataProcessor->processBuildingsBatch($this->buildings, $this->metadata);
    }
}
