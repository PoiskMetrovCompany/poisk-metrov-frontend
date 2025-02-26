<?php

namespace App\Console\Commands\Test;

use App\Services\YandexSearchService;
use Illuminate\Console\Command;

class SearchNameYandex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:search-name-yandex {--search=котики}';

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
        $yandexService = YandexSearchService::getFromApp();
        $searchName = $this->option('search');
        $result = $yandexService->getResultsByName($searchName);

        var_dump(json_decode($result));
    }
}
