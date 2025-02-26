<?php

namespace App\Console\Commands;

use Artisan;
use Illuminate\Console\Command;

class RefreshFeedWithFullUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-feed-with-full-update';

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
        Artisan::call("app:create-building-info --refresh=true");
        Artisan::call("app:update-data-from-transitory-tables");
        // Artisan::call("app:download-all-feeds --ignoreexisting=false --log=false");
        // Artisan::call("app:parse-feeds");
        // Artisan::call("app:merge-feeds");
        Artisan::call("app:clean-apartments");
        Artisan::call("app:cache-all");
        //Sprite creation should happen last
        Artisan::call("app:create-gallery-sprites");
    }
}
