<?php

namespace App\Http\Controllers\Api\V1\Export;

use App\Core\Interfaces\Repositories\CandidateProfilesRepositoryInterface;
use App\Exports\CandidateProfileExport;
use App\Exports\CandidateProfileSingleExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelTypes;

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
