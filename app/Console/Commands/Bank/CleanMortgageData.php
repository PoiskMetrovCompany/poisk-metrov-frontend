<?php

namespace App\Console\Commands\Bank;

use App\Models\MortgageCity;
use Illuminate\Console\Command;
use App\Models\Mortgage;
use App\Models\MortgageProgram;
use App\Models\MortgageProgramPivot;
use Illuminate\Support\Facades\DB;

class CleanMortgageData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-mortgage-data';

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MortgageCity::truncate();
        MortgageProgramPivot::truncate();
        Mortgage::truncate();
        MortgageProgram::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
