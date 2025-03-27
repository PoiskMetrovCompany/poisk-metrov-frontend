<?php

namespace App\Http\Controllers;

use App\Core\Services\PDFServiceInterface;
use App\Models\Apartment;
use App\Models\ResidentialComplex;
use App\Providers\AppServiceProvider;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Throwable;

/**
 * @see AppServiceProvider::registerPDFService()
 * @see PDFServiceInterface
 */
class PDFController extends Controller
{
    /**
     * @param PDFServiceInterface $pdfService
     */
    public function __construct(protected PDFServiceInterface $pdfService)
    {
    }

    /**
     * @param Request $request
     * @param string $buildingCode
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getBuildingPresentation(Request $request, string $buildingCode)
    {
        $filePathAndName = $this->pdfService->downloadBuildingPdf([$buildingCode]);
        $building = ResidentialComplex::where('code', $buildingCode)->first();
        $filePath = $filePathAndName[0];
        $fileName = "{$building->name}.pdf";
        $headers = ['ContentType' => 'application/pdf'];

        return response()->download($filePath, $fileName, $headers)->deleteFileAfterSend(true);
    }

    /**
     * @param Request $request
     * @param string $apartmentCode
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getApartmentPresentation(Request $request, string $apartmentCode)
    {
        $filePathAndName = $this->pdfService->downloadApartmentPdf([$apartmentCode]);
        $buildingName = Apartment::where('offer_id', $apartmentCode)->first()->residentialComplex->name;
        $filePath = $filePathAndName[0];
        $fileName = "{$buildingName}.pdf";
        $headers = ['ContentType' => 'application/pdf'];

        return response()->download($filePath, $fileName, $headers)->deleteFileAfterSend(true);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getFavoriteBuildingsPresentation(Request $request)
    {
        $filePathAndName = $this->pdfService->getFavoriteBuildingsPresentation();
        $filePath = $filePathAndName[0];
        $fileName = 'Избранные ЖК';
        $headers = ['ContentType' => 'application/pdf'];

        return response()->download($filePath, $fileName, $headers)->deleteFileAfterSend(true);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getFavoriteApartmentsPresentation(Request $request)
    {
        $filePathAndName = $this->pdfService->getFavoriteApartmentsPresentation();
        $filePath = $filePathAndName[0];
        $fileName = 'Избранные квартиры';
        $headers = ['ContentType' => 'application/pdf'];

        return response()->download($filePath, $fileName, $headers)->deleteFileAfterSend(true);
    }

    /**
     * @param Request $request
     * @param string $buildingCode
     * @return \Illuminate\Contracts\View\View
     */
    public function preview(Request $request, string $buildingCode)
    {
        $buildingData = $this->pdfService->getComplexesData([$buildingCode]);
        $view = View::make('export-complexes', ['complexesToPdf' => $buildingData]);

        try {
            return $view;
        } catch (Throwable $e) {
            Log::info('Error', [Response::view($view)]);
        }
    }

    /**
     * @param Request $request
     * @param string $offer_id
     * @return \Illuminate\Contracts\View\View
     */
    public function previewApartment(Request $request, string $offer_id)
    {
        $apartmentsData = $this->pdfService->getApartmentsData([$offer_id]);
        $view = View::make('export-apartments', ['apartments' => $apartmentsData]);

        try {
            return $view;
        } catch (Throwable $e) {
            Log::info('Error', [Response::view($view)]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function previewFavoriteBuildings(Request $request)
    {
        $buildingData = $this->pdfService->getFavoriteBuildings();
        $view = View::make('export-complexes', ['complexesToPdf' => $buildingData]);

        try {
            return $view;
        } catch (Throwable $e) {
            Log::info('Error', [Response::view($view)]);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getFavoriteApartmentsData(Request $request)
    {
        return $this->pdfService->getFavoriteApartments();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function previewFavoriteApartments(Request $request)
    {
        $apartmentsData = $this->pdfService->getFavoriteApartments();
        $view = View::make('export-apartments', ['apartments' => $apartmentsData]);

        try {
            return $view;
        } catch (Throwable $e) {
            Log::info('Error', [Response::view($view)]);
        }
    }
}
