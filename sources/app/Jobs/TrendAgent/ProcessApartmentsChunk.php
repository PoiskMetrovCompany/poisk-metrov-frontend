<?php

namespace App\Jobs\TrendAgent;

use App\Services\Scrapper\TrendAgent\DataProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Exception;

class ProcessApartmentsChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    private array $apartments;
    private array $metadata;

    public function __construct(array $data, array $metadata)
    {
        $this->apartments = $data['apartments'];
        $this->metadata = $metadata;

        $this->onQueue('trend_agent.apartments');
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
            $processor->processApartmentsBatch($this->apartments, $this->metadata);

            $this->updateProcessingStats();

        } catch (Exception $e) {
            throw $e;
        }
    }

    private function updateProcessingStats(): void
    {
        $key = "trend_agent:processing:{$this->metadata['session_id']}";

        Cache::increment("{$key}:processed_apartments", count($this->apartments));
        Cache::increment("{$key}:processed_chunks");
    }

    public function failed(Exception $exception): void
    {
        // уведомление администратору
        // Notification::sendAdmins(new ChunkProcessingFailed($this->metadata, $exception));
    }
}
