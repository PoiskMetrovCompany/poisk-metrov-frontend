<?php

namespace App\Console\Commands\Apartments;

use App\Models\Apartment;
use Illuminate\Console\Command;

class SetMissingFeedSourceToApartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-missing-feed-source-to-apartments {--feedname=}';

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
        $feedname = $this->option('feedname');
        $apartmentsWithNoFeedSource = Apartment::whereNull('feed_source')->count();
        echo "Will set feed source '$feedname' to $apartmentsWithNoFeedSource apartments" . PHP_EOL;
        Apartment::whereNull('feed_source')->update(['feed_source' => $feedname]);
    }
}
