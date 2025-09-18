<?php

namespace App\Http\Controllers\Api\V1\CandidateProfiles;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateProfiles\CandidateProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *     tags={"CandidateProfiles"},
 *     path="/api/v1/candidates/destroy",
 *     summary="Удаление анкет кандидатов по ключам",
 *     description="Возвращение JSON объекта с результатами удаления",  
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="keys",
 *         in="query",
 *         required=true,
 *         description="Массив ключей для удаления анкет. Можно передавать несколькими способами:
 *                      1) Через запятую: keys=value1,value2,value3
 *                      2) С квадратными скобками: keys[]=value1&keys[]=value2&keys[]=value3
 *                      3) Одиночный ключ: keys=value1",
 *         style="form",
 *         explode=true,
 *         @OA\Schema(
 *             type="array",
 *             @OA\Items(type="string", example="53cbb9a9-4bab-30ce-98f5-ed0277f4ada0")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="УСПЕХ!",
 *         @OA\JsonContent(
 *             @OA\Property(property="response", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Удалено 2 из 2 анкет"),
 *             @OA\Property(property="deleted_count", type="integer", example=2),
 *             @OA\Property(property="total_requested", type="integer", example=2),
 *             @OA\Property(property="deleted_keys", type="array", @OA\Items(type="string")),
 *             @OA\Property(property="not_found_keys", type="array", @OA\Items(type="string"))
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Некорректный запрос",
 *         @OA\JsonContent(
 *             @OA\Property(property="response", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Массив ключей обязателен")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Анкеты не найдены",
 *         @OA\JsonContent(
 *             @OA\Property(property="response", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Ни одна из запрошенных анкет не найдена")
 *         )
 *     )
 * )
 *
 * @param Request $request
 * @return JsonResponse
 */
class CandidateProfileDestroyController extends Controller
{
    public function __construct(
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
    )
    {

    }

    public function __invoke(Request $request)
    {
        $keys = [];

        $allParams = $request->query();

        if (isset($allParams['keys'])) {
            $keysParam = $allParams['keys'];

            if (is_array($keysParam)) {
                $keys = array_values($keysParam);
            }
            elseif (is_string($keysParam) && strpos($keysParam, ',') !== false) {
                $keys = array_map('trim', explode(',', $keysParam));
            }
            elseif (is_string($keysParam) && !empty($keysParam)) {
                $keys = [$keysParam];
            }
        }

        if (empty($keys)) {
            return new JsonResponse(
                data: [
                    'response' => false,
                    'message' => 'Массив ключей обязателен'
                ],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $keys = array_unique(array_filter($keys));

        if (empty($keys)) {
            return new JsonResponse(
                data: [
                    'response' => false,
                    'message' => 'Массив ключей не может быть пустым'
                ],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $candidateProfiles = $this->candidateProfilesRepository->getModel()::whereIn('key', $keys)->get();

        if ($candidateProfiles->isEmpty()) {
            return new JsonResponse(
                data: [
                    'response' => false,
                    'message' => 'Ни одна из запрошенных анкет не найдена',
                    'deleted_count' => 0,
                    'total_requested' => count($keys),
                    'deleted_keys' => [],
                    'not_found_keys' => $keys
                ],
                status: Response::HTTP_NOT_FOUND
            );
        }


        $foundKeys = $candidateProfiles->pluck('key')->toArray();

        $notFoundKeys = array_diff($keys, $foundKeys);

        $deletedCount = 0;
        $deletedKeys = [];

        foreach ($candidateProfiles as $candidateProfile) {
            if ($candidateProfile->forceDelete()) {
                $deletedCount++;
                $deletedKeys[] = $candidateProfile->key;
            }
        }

        $message = $this->generateResultMessage($deletedCount, count($keys), $notFoundKeys);

        return new JsonResponse(
            data: [
                'response' => $deletedCount > 0,
                'message' => $message,
                'deleted_count' => $deletedCount,
                'total_requested' => count($keys),
                'deleted_keys' => $deletedKeys,
                'not_found_keys' => $notFoundKeys
            ],
            status: $deletedCount > 0 ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Генерирует сообщение о результате удаления
     */
    private function generateResultMessage(int $deletedCount, int $totalRequested, array $notFoundKeys): string
    {
        if ($deletedCount === $totalRequested) {
            return "Удалено {$deletedCount} из {$totalRequested} анкет";
        }

        if ($deletedCount === 0) {
            return "Не удалось удалить ни одну из {$totalRequested} анкет";
        }

        $message = "Удалено {$deletedCount} из {$totalRequested} анкет";

        if (!empty($notFoundKeys)) {
            $notFoundCount = count($notFoundKeys);
            $message .= ". {$notFoundCount} анкет не найдено";
        }

        return $message;
    }
}
