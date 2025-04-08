<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class CbrController extends Controller
{
    /**
     * @OA\Schema(
     *       schema="Cbr/ActualDate",
     *       @OA\Property(
     *           property="status",
     *           type="string"
     *       ),
     *   	@OA\Property(
     *         property="error",
     *         type="string"
     *       )
     *  ),
     *
     * @OA\Get(
         * tags={"Cbr"},
         * path="/api/v1/cbr/actual-date/",
         * summary="Актуализация расписания заседаний директоров ЦБ",
         * description="Возвращение JSON объекта",
         * @OA\Response(
             * response=200,
             * description="УСПЕХ!",
         * ),
         * @OA\Response(
             * response=404,
             * description="Resource not found"
         * )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualDate(): JsonResponse
    {
        $cbr = Storage::disk('local')->get('cbr.json');
        $cbrObjectStorage = json_decode($cbr, true);
        $toDate = Carbon::now();
        $closestDate = null;

        foreach ($cbrObjectStorage as $value) {
        $stringDateFormat = "{$value['day_cbr']}.{$value['month_cbr']}.{$value['year_cbr']}";
        $date = Carbon::createFromFormat('d.m.Y', $stringDateFormat);

        if ($date->greaterThanOrEqualTo($toDate)) {
            if ($closestDate === null || $date->lessThan($closestDate)) {
                $closestDate = $date;
            }
        }
        }
        return response()->json(
            data: $closestDate ? $closestDate->format('Y-m-d') : null
        );
    }
}
