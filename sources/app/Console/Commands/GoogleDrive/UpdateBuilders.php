<?php

namespace App\Console\Commands\GoogleDrive;

use App\Services\BuilderService;
use Illuminate\Console\Command;

class UpdateBuilders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-builders';

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
        $builderService = BuilderService::getFromApp();
        $builderService->updateBuilders();
    }
}
