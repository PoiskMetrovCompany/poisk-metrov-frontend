<?php

namespace App\Console\Commands;

use App\Models\Gallery;
use App\Services\TextService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Str;

class CleanUpGallery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-up-gallery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all unavailable images from gallery database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gallery = Gallery::all();
        $textService = TextService::getFromApp();
        $i = 0;
        $count = $gallery->count();

        foreach ($gallery as $galleryItem) {
            $cleanUrl = $textService->cleanupNmarketImageURL($galleryItem->image_url);
            echo "$i/$count" . PHP_EOL;

            try {
                $imageHeaders = get_headers($cleanUrl);
                $status = explode(' ', $imageHeaders[0])[1];

                if ($status == '404') {
                    echo "Will delete $cleanUrl" . PHP_EOL;
                    $galleryItem->delete();
                }
            } catch (Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }

            $i++;
        }
    }
}
