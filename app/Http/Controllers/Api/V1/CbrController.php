<?php

namespace App\Http\Controllers\Api\V1;

use App\Core\Abstracts\AbstractOperations;
use App\Http\Controllers\Controller;
use App\Http\Resources\CbrResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class CbrController extends AbstractOperations
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
    public function actualDate(Request $request): JsonResponse
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
        return new JsonResponse(
            data: [
                ...self::identifier(),
                ...self::attributes(['date' => $closestDate->format('Y-m-d')]),
                ...self::metaData($request, $request->all()),
            ],
            status: Response::HTTP_OK
        );
    }

    public function getEntityClass(): string
    {
        return 'Cbr';
    }

    public function getResourceClass(): string
    {
        return CbrResource::class;
    }
}
