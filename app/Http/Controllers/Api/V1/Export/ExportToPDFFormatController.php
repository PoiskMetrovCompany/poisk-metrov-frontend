<?php

namespace App\Http\Controllers\Api\V1\Export;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Core\Interfaces\Repositories\RelatedDataRepositoryInterface;
use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Illuminate\Support\Facades\Log;
use ZipStream\ZipStream;
use ZipStream\Option\Archive as ZipStreamOptions;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @OA\Get(
 *     tags={"Export"},
 *     path="/api/v1/export/pdf-format",
 *     summary="Экспорт анкет кандидата в ПДФ (если передать ключ кандидата, то экспортирует анкету по ключу. Ключи нужно перечислять через запятую)",
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
 */
class ExportToPDFFormatController extends Controller
{
    public function __construct(
        protected VacancyRepositoryInterface $vacancyRepository,
        protected MaritalStatusesRepositoryInterface $maritalStatusesRepository,
        protected CandidateProfilesRepositoryInterface $candidateProfilesRepository,
        protected RelatedDataRepositoryInterface $relatedDataRepository
    )
    {

    }

    /**
     * @throws MpdfException
     * @throws \Throwable
     */
    public function __invoke(Request $request)
    {
        $keys = $request->input('keys');

        $query = DB::table('candidate_profiles')
            ->select('*')
            ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
            ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key');

        if ($keys) {
            $keys = array_map('trim', explode(',', $keys));
            $keys = array_filter($keys);
            if (empty($keys)) {
                abort(400, 'Не указаны ключи.');
            }
            $profiles = $query->whereIn('candidate_profiles.key', $keys)->get();
        } else {
            $profiles = $query->get();
        }

        if ($profiles->isEmpty()) {
            abort(404, 'Не найдено ни одной анкеты.');
        }

        // Очистка UTF-8
        $cleanUtf8 = function ($text) {
            if (!$text) return '';
            $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
            $text = str_replace(['“', '”', '‘', '’', '–', '—', '•'], ['"', '"', "'", "'", '-', '-', '*'], $text);
            return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        };

        // Если только один профиль — отдаём один PDF
        if ($profiles->count() === 1) {
            $data = $profiles->first();

            $dataArray = json_decode(json_encode($data), true);
            array_walk_recursive($dataArray, function (&$item) use ($cleanUtf8) {
                if (is_string($item)) {
                    $item = $cleanUtf8($item);
                }
            });
            $data = (object)$dataArray;

            $html = view('export-pdf.candidate-profiles.index', compact('data'))->render();

            try {
                $mpdf = new Mpdf([
                    'mode' => 'utf-8',
                    'format' => 'A4',
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                    'margin_left' => 15,
                    'margin_right' => 15,
                ]);

                $mpdf->SetDisplayMode('fullpage');
                $mpdf->WriteHTML($html);
                $mpdf->Output('Anketa_' . $data->key . '.pdf', 'D');
            } catch (MpdfException $e) {
                Log::error('mPDF Error: ' . $e->getMessage());
                abort(500, 'Ошибка генерации PDF.');
            }
        }
        // Если несколько — делаем архив с отдельными PDF
        else {
            $response = new StreamedResponse(function () use ($profiles, $cleanUtf8) {
                $options = new ZipStreamOptions();
                $options->setSendHttpHeaders(true);
                $zip = new ZipStream('Ankety_Kandidatov.zip', $options);

                foreach ($profiles as $profile) {
                    $dataArray = json_decode(json_encode($profile), true);
                    array_walk_recursive($dataArray, function (&$item) use ($cleanUtf8) {
                        if (is_string($item)) {
                            $item = $cleanUtf8($item);
                        }
                    });
                    $data = (object)$dataArray;

                    $html = view('export-pdf.candidate-profiles.index', compact('data'))->render();

                    try {
                        $mpdf = new Mpdf([
                            'mode' => 'utf-8',
                            'format' => 'A4',
                            'margin_top' => 10,
                            'margin_bottom' => 10,
                            'margin_left' => 15,
                            'margin_right' => 15,
                            'tempDir' => sys_get_temp_dir(), // важно для CLI
                        ]);
                        $mpdf->SetDisplayMode('fullpage');
                        $mpdf->WriteHTML($html);
                        $pdfContent = $mpdf->Output('', 'S'); // Получить как строку

                        $fileName = 'Anketa_' . $data->key . '.pdf';
                        $zip->addFile($fileName, $pdfContent);
                    } catch (MpdfException $e) {
                        Log::error("Ошибка генерации PDF для ключа {$data->key}: " . $e->getMessage());
                        // Можно пропустить с ошибкой или добавить текстовый файл
                        $zip->addFile("ERROR_{$data->key}.txt", "Ошибка генерации PDF: " . $e->getMessage());
                    }
                }

                $zip->finish();
            });

            return $response;
        }
    }
}
