<?php

namespace App\Http\Controllers\Api\V1\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use OpenApi\Annotations as OA;
use ZipArchive;

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
            ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
            ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key')
            ->select(
                'candidate_profiles.*',
                'vacancies.title as vacancy_name',
                'marital_statuses.title as marital_status_name',
                DB::raw("COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.name')), '') AS family_partner_name"),
                DB::raw("COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.age')), '') AS family_partner_age"),
                DB::raw("COALESCE(JSON_UNQUOTE(JSON_EXTRACT(candidate_profiles.family_partner, '$.relation')), '') AS family_partner_relation")
            );

        if ($keys) {
            $keys = urldecode($keys);
            $keys = array_map('trim', explode(',', $keys));
            $keys = array_filter($keys);
            if (empty($keys)) {
                abort(400, 'Не указаны ключи.');
            }
        }

        $cleanUtf8 = function ($text) {
            if (!$text) return '';
            $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
            $text = str_replace(['“', '”', '‘', '’', '–', '—', '•'], ['"', '"', "'", "'", '-', '-', '*'], $text);
            return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        };

        if ($keys && count($keys) === 1) {
            $profile = $query->where('candidate_profiles.key', $keys[0])->first();

            if (!$profile) {
                abort(404, 'Анкета не найдена.');
            }

            $dataArray = json_decode(json_encode($profile), true);
            array_walk_recursive($dataArray, function (&$item) use ($cleanUtf8) {
                if (is_string($item)) {
                    $item = $cleanUtf8($item);
                }
            });

            $familyPartner = [
                'name' => $dataArray['family_partner_name'] ?? '',
                'age' => $dataArray['family_partner_age'] ?? '',
                'relation' => $dataArray['family_partner_relation'] ?? '',
            ];

            $adultChildren = [];
            if (!empty($dataArray['adult_children'])) {
                $decoded = json_decode($dataArray['adult_children'], true);
                if (is_array($decoded)) {
                    $adultChildren = $decoded;
                }
            }

            $adultFamilyMembers = [];
            if (!empty($dataArray['adult_family_members'])) {
                $decoded = json_decode($dataArray['adult_family_members'], true);
                if (is_array($decoded)) {
                    $adultFamilyMembers = $decoded;
                }
            }

            $data = (object)$dataArray;

            $html = view('export-pdf.candidate-profiles.index', compact('data', 'familyPartner', 'adultChildren', 'adultFamilyMembers'))->render();

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
                $mpdf->WriteHTML($html);
                $mpdf->Output('Anketa_Kandidata.pdf', 'D');
            } catch (MpdfException $e) {
                abort(500, 'Ошибка генерации PDF.');
            }
        }  else {
            $profiles = $keys
                ? $query->whereIn('candidate_profiles.key', $keys)->get()
                : $query->get();

            if ($profiles->isEmpty()) {
                abort(404, 'Не найдено ни одной анкеты.');
            }

            $zip = new ZipArchive();
            $zipFileName = tempnam(sys_get_temp_dir(), 'zip_');

            if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
                abort(500, 'Не удалось создать ZIP-архив.');
            }

            try {
                foreach ($profiles as $profile) {
                    $dataArray = json_decode(json_encode($profile), true);
                    array_walk_recursive($dataArray, function (&$item) use ($cleanUtf8) {
                        if (is_string($item)) {
                            $item = $cleanUtf8($item);
                        }
                    });

                    $familyPartner = [
                        'name' => $dataArray['family_partner_name'] ?? '',
                        'age' => $dataArray['family_partner_age'] ?? '',
                        'relation' => $dataArray['family_partner_relation'] ?? '',
                    ];

                    $adultChildren = [];
                    if (!empty($dataArray['adult_children'])) {
                        $decoded = json_decode($dataArray['adult_children'], true);
                        if (is_array($decoded)) {
                            $adultChildren = $decoded;
                        }
                    }

                    $adultFamilyMembers = [];
                    if (!empty($dataArray['adult_family_members'])) {
                        $decoded = json_decode($dataArray['adult_family_members'], true);
                        if (is_array($decoded)) {
                            $adultFamilyMembers = $decoded;
                        }
                    }

                    $data = (object)$dataArray;

                    $html = view('export-pdf.candidate-profiles.index', compact('data', 'familyPartner', 'adultChildren', 'adultFamilyMembers'))->render();

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
                    $mpdf->WriteHTML($html);
                    $pdfContent = $mpdf->Output('', 'S');

                    $zip->addFromString("anketa_{$profile->key}.pdf", $pdfContent);
                }

                $zip->close();

                return response()->download($zipFileName, 'Ankety_Kandidatov.zip')->deleteFileAfterSend(true);

            } catch (\Exception $e) {
                if (file_exists($zipFileName)) {
                    unlink($zipFileName);
                }
                abort(500, 'Ошибка при создании архива: ' . $e->getMessage());
            }
        }
    }
}
