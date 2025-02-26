<?php

namespace App\Console\Commands;

use App\Http\Controllers\SpriteCreationController;
use App\Services\TextService;
use Illuminate\Console\Command;

class CreateGallerySprites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-gallery-sprites';

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
        $spriteController = new SpriteCreationController(TextService::getFromApp());
        $spriteController->createBuildingSprites();
    }
}
