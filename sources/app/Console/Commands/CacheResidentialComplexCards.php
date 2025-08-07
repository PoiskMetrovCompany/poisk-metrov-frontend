<?php

namespace App\Console\Commands;

use App\Services\CachingService;
use Illuminate\Console\Command;

class CacheResidentialComplexCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-residential-complex-cards';

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
        $cacher = CachingService::getFromApp();
        $cacher->cacheAllCards();
    }
}
