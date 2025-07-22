<?php

namespace App\Http\Controllers\Api\V1\Export;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Core\Interfaces\Repositories\MaritalStatusesRepositoryInterface;
use App\Core\Interfaces\Repositories\RelatedDataRepositoryInterface;
use App\Core\Interfaces\Repositories\VacancyRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
//    public function __invoke(Request $request)
//    {
//        if ($request->input('keys')) {
//            $keys = explode(",", $request->input('keys'));
//            foreach ($keys as $key) {
//                $candidateProfilesKey = $key;
//
//                $data = DB::table('candidate_profiles')
//                    ->select('*')
//                    ->where('candidate_profiles.key', '=', $candidateProfilesKey)
//                    ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
//                    ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key')
//                    ->first();
//
//                $cleanUtf8 = function ($text) {
//                    if (!$text) return '';
//                    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
//                    $text = str_replace(['“', '”', '‘', '’', '–', '—', '•'], ['"', '"', "'", "'", '-', '-', '*'], $text);
//                    return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
//                };
//
//                $dataArray = json_decode(json_encode($data), true);
//                array_walk_recursive($dataArray, function (&$item) use ($cleanUtf8) {
//                    if (is_string($item)) {
//                        $item = $cleanUtf8($item);
//                    }
//                });
//                $data = (object)$dataArray;
//
//                $html = view('export-pdf.candidate-profiles.index', compact('data'))->render();
//
//                try {
//                    $mpdf = new Mpdf([
//                        'mode' => 'utf-8',
//                        'format' => 'A4',
//                        'debug' => true,
//                    ]);
//                    $mpdf->WriteHTML($html);
//                    $mpdf->Output("Anketa_{$data->last_name}.pdf", 'D');
//                } catch (MpdfException $e) {
//                    \Log::error('mPDF Error: ' . $e->getMessage());
//                    \Log::error('HTML sample: ' . substr(strip_tags($html), 0, 300));
//                    abort(500, 'Ошибка генерации PDF. Проверьте данные.');
//                }
//            }
//        }
//    }

    public function __invoke(Request $request)
    {
        $keys = $request->input('keys');
        if (!$keys) {
            abort(400, 'Параметр "keys" обязателен.');
        }

        $keys = array_map('trim', explode(',', $keys));
        if (empty($keys)) {
            abort(400, 'Не указаны ключи.');
        }

        $allHtml = '';

        foreach ($keys as $key) {
            $data = DB::table('candidate_profiles')
                ->select('*')
                ->where('candidate_profiles.key', '=', $key)
                ->join('vacancies', 'vacancies.key', '=', 'candidate_profiles.vacancies_key')
                ->join('marital_statuses', 'marital_statuses.key', '=', 'candidate_profiles.marital_statuses_key')
                ->first();

            if (!$data) {
                Log::warning("Анкета не найдена для ключа: {$key}");
                continue;
            }

            $cleanUtf8 = function ($text) {
                if (!$text) return '';
                $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
                $text = str_replace(['“', '”', '‘', '’', '–', '—', '•'], ['"', '"', "'", "'", '-', '-', '*'], $text);
                return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            };

            $dataArray = json_decode(json_encode($data), true);
            array_walk_recursive($dataArray, function (&$item) use ($cleanUtf8) {
                if (is_string($item)) {
                    $item = $cleanUtf8($item);
                }
            });
            $data = (object)$dataArray;

            $html = view('export-pdf.candidate-profiles.index', compact('data'))->render();

            $allHtml .= $html;

            $allHtml .= '<div style="page-break-after: always;"></div>';
        }

        if (empty($allHtml)) {
            abort(404, 'Не найдено ни одной анкеты для указанных ключей.');
        }

        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'debug' => false,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 15,
                'margin_right' => 15,
            ]);

            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($allHtml);

            $mpdf->Output('Ankety_Kandidatov.pdf', 'D');
        } catch (MpdfException $e) {
            Log::error('mPDF Error: ' . $e->getMessage());
            Log::error('HTML sample: ' . substr(strip_tags($allHtml), 0, 500));
            abort(500, 'Ошибка генерации PDF. Проверьте данные.');
        }
    }

}
