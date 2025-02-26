<?php

namespace App\Console\Commands\Feeds;

use App\Models\Avito\AvitoApartment;
use App\Models\Avito\AvitoBuilding;
use App\Models\Avito\AvitoImage;
use App\Models\Avito\AvitoLocation;
use App\Models\Avito\AvitoResidentialComplex;
use App\Models\RealtyFeed\RealtyFeedApartment;
use App\Models\RealtyFeed\RealtyFeedBuilding;
use App\Models\RealtyFeed\RealtyFeedImage;
use App\Models\RealtyFeed\RealtyFeedLocation;
use App\Models\RealtyFeed\RealtyFeedResidentialComplex;
use App\Models\Complex\ComplexApartment;
use App\Models\Complex\ComplexBuilding;
use App\Models\Complex\ComplexImage;
use App\Models\Complex\ComplexLocation;
use App\Models\Complex\ComplexResidentialComplex;
use App\Models\Version2\Version2Apartment;
use App\Models\Version2\Version2Building;
use App\Models\Version2\Version2Image;
use App\Models\Version2\Version2Location;
use App\Models\Version2\Version2ResidentialComplex;
use App\Services\FeedService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class ParseFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-feeds';

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
        // Schema::disableForeignKeyConstraints();
        // RealtyFeedLocation::truncate();
        // RealtyFeedResidentialComplex::truncate();
        // RealtyFeedImage::truncate();
        // RealtyFeedBuilding::truncate();
        // RealtyFeedApartment::truncate();
        // AvitoLocation::truncate();
        // AvitoResidentialComplex::truncate();
        // AvitoImage::truncate();
        // AvitoBuilding::truncate();
        // AvitoApartment::truncate();
        // ComplexLocation::truncate();
        // ComplexResidentialComplex::truncate();
        // ComplexImage::truncate();
        // ComplexBuilding::truncate();
        // ComplexApartment::truncate();
        // Version2Location::truncate();
        // Version2ResidentialComplex::truncate();
        // Version2Image::truncate();
        // Version2Building::truncate();
        // Version2Apartment::truncate();
        $feedService = FeedService::getFromApp();
        $feedService->parseAllFeeds();
        // Schema::enableForeignKeyConstraints();
    }
}
