<?php

namespace App\Http\Controllers\Api\V1\Export;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Exports\CandidateProfileExport;
use App\Exports\CandidateProfileSingleExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelTypes;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     tags={"Export"},
 *     path="/api/v1/export/xlsx-format",
 *     summary="Экспорт анкет кандидата в таблицу (если передать ключ кандидата, то экспортирует анкету по ключу. Ключи нужно перечислять через запятую)",
 *     description="Возвращение JSON объекта",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="keys",
 *         in="query",
 *         required=false,
 *         description="Ключ для получения анкеты",
 *         @OA\Schema(type="string", example="53cbb9a9-4bab-30ce-98f5-ed0277f4ada0")
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
class ExportToXlsxFormatController extends Controller
{
    public function __construct(
        private CandidateProfilesRepositoryInterface $repository
    )
    {

    }

    public function __invoke(Request $request) {
        $export = $request->filled('keys')
            ? new CandidateProfileSingleExport($request->input('keys'), $this->repository)
            : new CandidateProfileExport($this->repository);

        return Excel::download($export, 'candidateProfiles.xlsx', ExcelTypes::XLSX);
    }
}
