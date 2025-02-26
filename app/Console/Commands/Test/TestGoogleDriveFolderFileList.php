<?php

namespace App\Console\Commands\Test;

use App\Services\GoogleDriveService;
use Illuminate\Console\Command;

class TestGoogleDriveFolderFileList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-google-drive-folder-file-list {--fileId=}';

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
        $fileId = $this->option('fileId');
        $fileList = $googleService->getFileListFromFolder($fileId);
        var_dump($fileList->getFiles());
    }
}
