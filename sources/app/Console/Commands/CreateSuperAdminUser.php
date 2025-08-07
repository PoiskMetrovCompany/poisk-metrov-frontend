<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateSuperAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-super-admin-user {name} {phone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создаёт администратора';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = Str::random(8);

        $admin = new User();
        $admin->name = $this->argument('name');
        $admin->role = 'Администратор';
        $admin->phone = $this->argument('phone');
        $admin->password = Hash::make($password);

        if (!User::where('phone', $this->argument('phone'))->first()) {
            $admin->save();
            $this->info('Администратор успешно создан!');
            $this->info("Данные для входа в панель администратора:\n Логин: {$admin->name}\nПароль: {$password}" );
        } else {
            $this->error('Возникла ошибка при создании администратора!');
        }
    }
}
