<?php

namespace App\Services;

use App\CRM\cURL;
use App\Telegram\TelegramResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class TelegramService.
 */
class TelegramService extends AbstractService
{
    protected string $url;
    protected string $token;
    protected string $defaultGroup;
    //Как проверять локально
    //1 - запустить локальный local tunnel командой lt --port 8000 --local-host "localhost"
    //2 - вписать созданную ссылку в поле webhook в конфиге
    //3 - выполнить команду php artisan app:update-telegram-webhook чтобы назначился новый вебхук
    //Теперь коллбэки будут приходить в функцию callback
    protected string $webhook;
    protected bool $usePassword = false;
    protected array|null $auth = null;

    public function __construct(protected CityService $cityService)
    {
        $config = Storage::json('telegram.json');

        if (! $config) {
            Log::error('Telegram config not found!');
            return;
        }

        if (Storage::fileExists('site-auth.json')) {
            $this->auth = Storage::json('site-auth.json');
        }

        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function updateWebhookFromConfig()
    {
        $this->setWebhook($this->webhook);
    }

    public function setWebhook(string $webhook)
    {
        $parameters = [];

        if ($this->auth != null && $this->usePassword) {
            $webhook = str_replace('https://', "https://{$this->auth['user']}:{$this->auth['password']}@", $webhook);
        }

        $parameters['url'] = "{$webhook}/api/faweik3w4pofja23zcn23p1qpjzxkcnelrjq";

        Log::info('Will update webhook');
        // Log::info($parameters['url']);

        $this->sendRequest('/setWebhook', $parameters);
    }

    public function sendMessageToTestGroup(string $message, array $parameters = [])
    {
        return $this->sendMessage($message, $this->defaultGroup, $parameters);
    }

    public function sendMessage(string $message, string $groupOrUserId, array $parameters = [], array $replyParameters = [])
    {
        $parameters['chat_id'] = $groupOrUserId;
        $parameters['text'] = $message;

        if (count($replyParameters) > 0) {
            $parameters['reply_parameters'] = $replyParameters;
        }

        return $this->sendRequest('/sendMessage', $parameters);
    }

    public function createChatInviteLink(string $chatId, string $city)
    {
        $parameters['chat_id'] = $chatId;
        $parameters['name'] = 'Поиск метров ' . $this->cityService->cityCodes[$city];
        $parameters['member_limit'] = 1;

        return $this->sendRequest('/createChatInviteLink', $parameters, false);
    }

    public function editMessage(string $message, string $messageId, string $chatId, array $parameters = [])
    {
        $parameters['chat_id'] = $chatId;
        $parameters['message_id'] = $messageId;
        $parameters['text'] = $message;

        return $this->sendRequest('/editMessageText', $parameters);
    }

    public function editMessageReplyMarkup(string $messageId, string $chatId, array $markup)
    {
        $parameters['chat_id'] = $chatId;
        $parameters['message_id'] = $messageId;
        $parameters['reply_markup'] = $markup;

        return $this->sendRequest('/editMessageReplyMarkup', $parameters);
    }

    public function deleteMessage(string $messageId, string $groupOrUserId)
    {
        $parameters = [];
        $parameters['chat_id'] = $groupOrUserId;
        $parameters['message_id'] = $messageId;

        return $this->sendRequest('/deleteMessage', $parameters);
    }

    public function getUserProfilePhoto(int $user_id)
    {
        return $this->sendRequest('/getUserProfilePhotos', ['user_id' => $user_id, 'limit' => 1], false);
    }

    public function getFile(string $fileId)
    {
        return $this->sendRequest('/getFile', ['file_id' => $fileId], false);
    }

    public function getFileUrl(string $filePath): string
    {
        return "https://api.telegram.org/file/bot{$this->token}/{$filePath}";
    }

    //Also used to kick users without banning them!
    public function unbanChatMember(string $chatId, string $userId, bool $onlyIfBanned = false)
    {
        $parameters['chat_id'] = $chatId;
        $parameters['user_id'] = $userId;
        $parameters['only_if_banned'] = $onlyIfBanned;

        return $this->sendRequest('/unbanChatMember', $parameters);
    }

    public function getUserPhotoUrl(int $userId)
    {
        $photos = $this->getUserProfilePhoto($userId);

        if (count($photos->result->photos[0]) == 0) {
            return '';
        }

        $fileId = $photos->result->photos[0][0]->file_id;
        $file = $this->getFile($fileId);
        $filePath = $file->result->file_path;

        return $this->getFileUrl($filePath);
    }

    //Parameters - JSON encoded as string
    protected function sendRequest(string $path, array $parameters, bool $createResponse = true, $method = CURLOPT_POST)
    {
        $response = cURL::sendRequest("{$this->url}{$this->token}{$path}", json_encode($parameters), $method);

        if (! \App::isProduction()) {
            Log::info(json_encode($response));
        }

        if ($createResponse) {
            $telegramResponse = new TelegramResponse($response);

            return $telegramResponse;
        } else {
            return $response;
        }
    }

    public static function getFromApp(): TelegramService
    {
        return parent::getFromApp();
    }
}
