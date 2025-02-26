<?php

namespace App\Console\Commands;

use App\Models\Manager;
use App\Models\User;
use Illuminate\Console\Command;

class CreateManagerFromUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-manager-from-user {--userid=}';

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
        $userId = $this->option('userid');
        $user = User::where('id', $userId)->first();

        if (! $user) {
            echo "User with id {$userId} not found" . PHP_EOL;

            return;
        }

        $data = [
            'phone' => $user->phone,
            'document_name' => "{$user->name} {$user->surname}",
            'city' => $user->crm_city,
            'user_id' => $user->id
        ];

        if (! Manager::where($data)->exists()) {
            Manager::create($data);

            echo "Created manager for user #{$userId}" . PHP_EOL;
        } else {
            echo "User #{$userId} already has a manager" . PHP_EOL;
        }
    }
}
