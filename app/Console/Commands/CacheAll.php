<?php

namespace App\Console\Commands;

use App\Services\CachingService;
use App\Services\SearchService;
use Illuminate\Console\Command;

class CacheAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-all';

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
        $cacher->cacheSearchFilterData(SearchService::getFromApp());
        $cacher->cacheAllCards();
        $cacher->cacheResidentialComplexSearchData();
    }
}
