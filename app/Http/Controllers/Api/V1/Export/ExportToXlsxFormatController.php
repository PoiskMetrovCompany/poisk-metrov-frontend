<?php

namespace App\Http\Controllers\Api\V1\Export;

use App\Exports\CandidateProfileExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelTypes;

class ExportToXlsxFormatController extends Controller
{
    public function __invoke(Request $request) {
        return Excel::download(
            new CandidateProfileExport(),
            'candidateProfiles.xlsx',
            ExcelTypes::XLSX
        );
    }
}
