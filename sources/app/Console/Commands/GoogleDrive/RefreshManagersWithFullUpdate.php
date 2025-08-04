<?php

namespace App\Console\Commands\GoogleDrive;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RefreshManagersWithFullUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-managers-with-full-update';

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
        Artisan::call("app:download-manager-lists");
        Artisan::call("app:load-manager-data");
    }
}
