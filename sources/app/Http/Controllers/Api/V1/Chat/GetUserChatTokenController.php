<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Core\Abstracts\AbstractOperations;
use App\Core\Interfaces\Repositories\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMessages\ChatUserTokenResource;
use App\Models\ChatMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class GetUserChatTokenController extends AbstractOperations
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {

    }

    /**
     * @OA\Get(
     *      tags={"Chat"},
     *      path="/api/v1/chats/get-user-token",
     *      summary="Выдача токена для чата",
     *      description="Возвращение JSON объекта",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          required=true,
     *          description="ID клиента",
     *          @OA\Schema(type="integer", example="")
     *      ),
     *      @OA\Response(response=200, description="УСПЕХ!"),
     *      @OA\Response(
     *          response=404,
     *          description="Resource not found"
     *      )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $token = '';
        $user = $this->userRepository->findById($request->input('user_id'));
        //Этот кук доступен только на бэке (secure = true), поэтому с фронта делаем запрос сюда и получаем кук
        $chatTokenCookie = Cookie::get('chat_token');

        if ($chatTokenCookie) {
            if ($user) {
                if (! $user->chat_token) {
                    $user->update(['chat_token' => $chatTokenCookie]);
                }

                $chatTokenCookie = $user->chat_token;
            }

            $collect = new ChatUserTokenResource(['hasToken' => true, 'token' => $chatTokenCookie]);
            return new JsonResponse(
                data: [
                    ...self::identifier(),
                    ...self::attributes($collect),
                    ...self::metaData($request, $request->all())
                ],
                status: Response::HTTP_OK
            );
        }

        if ($user) {
            if (! $user->chat_token) {
                $user->update(['chat_token' => Str::random(32)]);
            }

            $token = $user->chat_token;
        } else {
            $token = Str::random(32);
        }

        $collect = new ChatUserTokenResource(['hasToken' => true, 'token' => $token]);
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes($collect),
                ...self::metaData($request, $request->all())
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return ChatMessages::class;
    }

    public function getResourceClass(): string
    {
        return ChatUserTokenResource::class;
    }
}
