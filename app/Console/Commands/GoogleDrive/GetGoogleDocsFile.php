<?php

namespace App\Console\Commands\GoogleDrive;

use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GetGoogleDocsFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-google-docs-file {--documentid=} {--format=}';

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
        $documentId = $this->option('documentid');
        $format = $this->option('format');
        $googleDriveService = GoogleDriveService::getFromApp();
        $document = $googleDriveService->getDocument($documentId);
        Storage::put("$documentId.$format", $document);
    }
}
