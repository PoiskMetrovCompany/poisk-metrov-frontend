<?php

namespace App\Console\Commands\Feeds;

use App\Services\FeedService;
use Illuminate\Console\Command;

class DownloadAllFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-all-feeds {--ignoreexisting=false} {--log=false}';

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
        $ignoreExisting = $this->option('ignoreexisting');
        $log = $this->option('log');

        if ($ignoreExisting == null) {
            $ignoreExisting = false;
        } else {
            $ignoreExisting = filter_var($ignoreExisting, FILTER_VALIDATE_BOOLEAN);
        }

        if ($log == null) {
            $log = false;
        }

        $feedService->downloadAllFeeds($log, $ignoreExisting);
    }
}
