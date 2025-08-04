<?php

namespace App\Console\Commands\GoogleDrive;

use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetGoogleDriveFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-google-drive-file {--fileid=} {--format=}';

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
        $fileId = $this->option('fileid');
        $format = $this->option('format');
        $googleDriveService = GoogleDriveService::getFromApp();
        $fileContents = $googleDriveService->getFile($fileId);
        Storage::put("$fileId.$format", $fileContents);
    }
}
