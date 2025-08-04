<?php

namespace App\Console\Commands\GoogleDrive;

use App\Services\CityService;
use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadManagerLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:download-manager-lists';

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
        $googleManagers = Storage::json('google-managers.json');
        $cities = CityService::getFromApp()->possibleCityCodes;
        $googleDriveService = GoogleDriveService::getFromApp();

        foreach ($cities as $city) {
            $fileId = $googleManagers[$city]['fileId'];
            $fileContents = $googleDriveService->getDocument($fileId);
            Storage::put("managers/$city/{$googleManagers[$city]['fileName']}", $fileContents);
        }
    }
}
