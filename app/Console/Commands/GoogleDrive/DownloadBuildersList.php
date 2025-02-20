<?php

namespace App\Console\Commands\GoogleDrive;

use Illuminate\Console\Command;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Storage;

class DownloadBuildersList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-builders-list';

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
        $googleBuilders = Storage::json('google-builders.json');
        $googleDriveService = GoogleDriveService::getFromApp();
        $fileId = $googleBuilders['fileId'];
        $fileContents = $googleDriveService->getDocument($fileId);
        Storage::put("builders/builders.xlsx", $fileContents);
    }
}
