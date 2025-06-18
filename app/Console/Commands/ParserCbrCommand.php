<?php

namespace App\Console\Commands;

use App\Services\Parser\ParserCbrService;
use Illuminate\Console\Command;

class ParserCbrCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parser-cbr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Команда для запуска парсера дат заседаний';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $parserServiceCbr = new ParserCbrService();
        $parserServiceCbr->handle();
    }
}
