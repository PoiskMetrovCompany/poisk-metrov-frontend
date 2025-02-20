<?php

namespace App\Console\Commands\Feeds;

use App\Services\FeedService;
use Illuminate\Console\Command;

class CreateHardcodedFeedEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-hardcoded-feed-entries';

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
        $feedService->createFeedEntries();
    }
}
