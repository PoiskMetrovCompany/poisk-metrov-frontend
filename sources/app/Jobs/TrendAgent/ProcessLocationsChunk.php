<?php

namespace App\Jobs\TrendAgent;

use App\Services\Scrapper\TrendAgent\DataProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class ProcessLocationsChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    private array $locations;
    private array $metadata;

    public function __construct(array $data, array $metadata)
    {
        $this->locations = $data['locations'];
        $this->metadata = $metadata;

        $this->onQueue('trend_agent.locations');
        $this->delay($this->calculateDelay());
    }

    private function calculateDelay(): int
    {
        return match($this->metadata['priority'] ?? 'normal') {
            'high' => 0,
            'normal' => 30,
            'low' => 120,
            default => 60
        };
    }

    public function handle(DataProcessor $processor): void
    {
        try {
            $processor->processLocationsBatch($this->locations, $this->metadata);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function failed(Exception $exception): void
    {
        Log::error('Locations chunk processing failed permanently', [
            'error' => $exception->getMessage(),
            'chunk_index' => $this->metadata['chunk_index'],
            'city' => $this->metadata['city'],
            'session_id' => $this->metadata['session_id']
        ]);
    }
}
