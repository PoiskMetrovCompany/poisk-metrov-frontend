<?php

namespace App\Console\Commands\Feeds;

use App\Services\FeedService;
use Illuminate\Console\Command;

class MergeFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:merge-feeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $feedService = FeedService::getFromApp();
        $feedService->mergeFeeds();
    }
}
