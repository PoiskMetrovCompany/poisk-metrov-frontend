<?php

namespace App\Http\Controllers\Api\V1\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @OA\Get(
 *     tags={"Export"},
 *     path="/api/v1/export/pdf-format",
 *     summary="Экспорт анкет кандидатов в PDF",
 *     description="Экспорт одной или нескольких анкет кандидатов в одном PDF-документе. Ключи передаются через запятую в параметре 'keys'.",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="keys",
 *         in="query",
 *         required=false,
 *         description="Ключи кандидатов, разделённые запятой. Пример: 53cbb9a9-...,fc8e5010-...",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Успешная генерация PDF",
 *         @OA\MediaType(
 *             mediaType="application/pdf",
 *             @OA\Schema(type="string", format="binary")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Не указаны ключи"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Анкеты не найдены"
 *     )
 * )
 */
class ExportToPDFFormatController extends Controller
{
    /**
     * @throws MpdfException
     */
    public function __invoke(Request $request)
    {
        $keys = $request->input('keys');

        $query = DB::table('candidate_profiles')
            ->select('*')
            ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
            ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key');

        if ($keys) {
            $keys = urldecode($keys);
            $keys = array_map('trim', explode(',', $keys));
            $keys = array_filter($keys);

            if (empty($keys)) {
                abort(400, 'Не указаны ключи.');
            }

            $profiles = $query->whereIn('candidate_profiles.key', $keys)->get();
            Log::info('Запрошены ключи:', ['keys' => $keys]);
            Log::info('Найдено анкет:', ['count' => $profiles->count(), 'found_keys' => $profiles->pluck('key')->toArray()]);
        } else {
            $profiles = $query->get();
            Log::info('Export PDF: экспорт всех анкет', ['count' => $profiles->count()]);
        }

        if ($profiles->isEmpty()) {
            abort(404, 'Не найдено ни одной анкеты.');
        }

        $cleanUtf8 = function ($text) {
            if (!$text) return '';
            $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
            $text = str_replace(['“', '”', '‘', '’', '–', '—', '•'], ['"', '"', "'", "'", '-', '-', '*'], $text);
            return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        };

        $html = '';

        $html .= '
            <h1 style="text-align: center;">Анкеты кандидатов</h1>
            <p style="text-align: center; color: #555;">Всего: ' . $profiles->count() . '</p>
            <hr>
            <div style="page-break-before: always;"></div>
        ';

        foreach ($profiles as $profile) {
            $dataArray = json_decode(json_encode($profile), true);
            array_walk_recursive($dataArray, function (&$item) use ($cleanUtf8) {
                if (is_string($item)) {
                    $item = $cleanUtf8($item);
                }
            });
            $data = (object)$dataArray;

            $html .= view('export-pdf.candidate-profiles.index', compact('data'))->render();

            $html .= '<div style="page-break-before: always;"></div>';
        }

        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 15,
                'margin_right' => 15,
                'tempDir' => storage_path('app/temp/mpdf'),
                'default_font' => 'dejavusans',
            ]);

            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($html);
            $mpdf->Output('Ankety_Kandidatov.pdf', 'D');
        } catch (MpdfException $e) {
            abort(500, 'Ошибка генерации PDF.');
        }
    }
}
