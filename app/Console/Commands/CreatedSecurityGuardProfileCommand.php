<?php

namespace App\Console\Commands;

use App\Core\Common\RoleEnum;
use App\Models\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreatedSecurityGuardProfileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:created-security-guard-profile-command {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание профиля безопасника';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = Str::random(8);
        $account = new Account();
        $account->key = Str::uuid()->toString();
        $account->role = RoleEnum::SecurityGuard->value;
        $account->email =  $this->argument('email');
        $account->secret = Hash::make($password);

        if (!Account::where('email', $this->argument('email'))->first()) {
            $account->save();
            $this->info('Профиль безопасника успешно создан!');
            $this->info("Данные для входа в панель безопасника:\n Логин: {$account->email}\nПароль: {$password}" );
        } else {
            $this->error('Ошибка создания аккаунта безопасника!');
        }
    }
}
