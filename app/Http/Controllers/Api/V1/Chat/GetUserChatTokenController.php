<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class GetUserChatTokenController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        //Этот кук доступен только на бэке (secure = true), поэтому с фронта делаем запрос сюда и получаем кук
        $chatTokenCookie = Cookie::get('chat_token');

        //Если токен есть
        if ($chatTokenCookie) {
            //Если юзер авторизован, но токена нет, то даем ему текущий токен
            if ($user) {
                if (! $user->chat_token) {
                    $user->update(['chat_token' => $chatTokenCookie]);
                }

                $chatTokenCookie = $user->chat_token;
            }

            //К этому моменту у юзера точно есть токен, но если он был другой, то все равно переназначаем
            //чтобы когда сессия истечет он не появлися бы как новый пользователь
            $chatTokenCookieObject = Cookie::make('chat_token', $chatTokenCookie, time() + 31536000, '/', null, true);

            return response()->json(['hasToken' => true, 'token' => $chatTokenCookie])->withCookie($chatTokenCookieObject);
        }

        //Если токена нет, то надо создать
        $token = '';

        //Берем токен у авторизованного пользователя
        if ($user) {
            //Если токена нет, то создаем, затем берем
            if (! $user->chat_token) {
                $user->update(['chat_token' => Str::random(32)]);
            }

            $token = $user->chat_token;
        } else {
            //Просто делаем токен. При авторизации он запишется в поле пользователя
            $token = Str::random(32);
        }

        $chatTokenCookie = Cookie::make('chat_token', $token, time() + 31536000, '/', null, true);

        return response()->json(['hasToken' => true, 'token' => $token])->withCookie($chatTokenCookie);
    }
}
