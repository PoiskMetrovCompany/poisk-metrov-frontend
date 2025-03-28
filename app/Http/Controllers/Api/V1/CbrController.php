<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CbrController extends Controller
{
    public function actualDate(){
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
