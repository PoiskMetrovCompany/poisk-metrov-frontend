<?php

namespace App\Console\Commands\Cache;

use Illuminate\Console\Command;

class UpdateApartmentSelectionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-apartment-selections-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление кэша для подборок квартир';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // TODO: сделать после реализации АПИ
    }
}
