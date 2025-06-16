<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class FeedSynchronizationQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $city,
        protected string $fileName,
        protected string $extension
    ) {}

    public function handle(): void
    {

        Artisan::call(
        'app:loading-feed-from-trend-agent-command', [
            'city' => $this->city,
            'fileName' => $this->fileName,
            'extension' => $this->extension
        ]);
    }
}
