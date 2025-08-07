<?php

namespace App\Console\Commands;

use App\Models\CRMSyncRequiredForUser;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateUsersInCRM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-users-in-crm';

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
        $usersToUpdate = CRMSyncRequiredForUser::get()->pluck('user_id');

        foreach ($usersToUpdate as $userId) {
            User::where('id', $userId)->first()->syncWithLead();
        }

        CRMSyncRequiredForUser::truncate();
    }
}
