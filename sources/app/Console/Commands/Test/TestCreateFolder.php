<?php

namespace App\Console\Commands\Test;

use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Storage;

class TestCreateFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-create-folder {--name=}';

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
        $googleService = GoogleDriveService::getFromApp();
        $parents = [Storage::json('telegram-survey.json')['documentFolderId']];
        $folder = $googleService->createFolder($this->option('name'), $parents);
        echo $folder->name . PHP_EOL;
        var_dump($folder);
    }
}
