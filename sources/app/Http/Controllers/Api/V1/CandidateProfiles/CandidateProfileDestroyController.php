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
 *     summary="Удаление анкеты кандидата по ключу",
 *     description="Возвращение JSON объекта",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="key",
 *         in="query",
 *         required=true,
 *         description="Ключи для удаления анкет(ы)",
 *         @OA\Schema(type="string", example="53cbb9a9-4bab-30ce-98f5-ed0277f4ada0,53cbb9a9-4bab-30ce-98f5-ed0277f4ada0")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="УСПЕХ!",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Иван Иванов"),
 *             @OA\Property(property="email", type="string", example="ivan@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Анкета не найдена")
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
        $keys = explode(',', $request->input('keys'));
        $candidateProfile = $this->candidateProfilesRepository->find(['key' => $keys]);
        $dataCollection = new CandidateProfileResource($candidateProfile);
        $repository = $candidateProfile->delete();

        return new JsonResponse(
            data: [
                'response' => $repository,
                'attributes' => $dataCollection,
            ],
            status: Response::HTTP_OK
        );
    }
}
