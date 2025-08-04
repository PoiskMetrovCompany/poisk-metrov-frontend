<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TextService;
use Illuminate\Console\Command;
use Str;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-test-user {--phone=} {--name=none}';

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
        $phone = $this->option('phone');

        if ($phone === null) {
            echo 'Введите телефон в опцию phone' . PHP_EOL;
            return;
        }

        $phone = TextService::getFromApp()->formatPhone($phone);

        if (! Str::startsWith($phone, ['+'])) {
            echo 'Телефон должен начинаться с +' . PHP_EOL;
            return;
        }

        if (User::where('phone', $phone)->exists()) {
            echo 'Пользователь с таким телефоном уже существует' . PHP_EOL;
            return;
        }

        $name = $this->option('name');

        if ($name === 'none') {
            $name = 'Test user ' . User::where('is_test', '1')->count() + 1;
        }

        $user = new User([
            'name' => $name,
            'phone' => $phone,
            'is_test' => true
        ]);

        $user->save();
        echo "Создан тестовый пользователь $name с телефоном $phone" . PHP_EOL;
    }
}
