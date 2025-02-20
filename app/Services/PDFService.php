<?php

namespace App\Services;

use App\Http\Resources\ApartmentResource;
use Illuminate\Http\Request;
use App\Http\Resources\ResidentialComplexResource;
use App\Http\Resources\ResidentialComplexCardResource;
use App\Models\ResidentialComplex;
use App\Models\Renovation;
use App\Models\Apartment;
use App\TextFormatters\PriceTextFormatter;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

/**
 * Class PDFService.
 */
class PDFService
{
    public function __construct(protected FavoritesService $favoritesService)
    {
    }

    public function getFavoriteBuildingsPresentation()
    {
        return $this->downloadBuildingPdf($this->favoritesService->getFavoriteBuildingCodes());
    }

    public function getFavoriteApartmentsPresentation()
    {
        return $this->downloadApartmentPdf($this->favoritesService->getFavoritePlanOfferIds());
    }

    public function downloadBuildingPdf(array $buildingCodes): array
    {
        $adapter = 'public_classic';
        $buildingData = $this->getComplexesData($buildingCodes);
        $html = view('export-complexes', ['complexesToPdf' => $buildingData])->render();
        Storage::disk($adapter)->makeDirectory('temp');
        $fileName = "$buildingCodes[0].pdf";
        $filePath = "temp/$fileName";
        Browsershot::html($html)->waitForSelector('ymaps')->waitUntilNetworkIdle()->paperSize(600, 840, 'px')->savePdf("$filePath");

        return [$filePath, $fileName];
    }

    public function downloadApartmentPdf(array $apartmentCodes): array
    {
        $adapter = 'public_classic';
        $apartmentsData = $this->getApartmentsData($apartmentCodes);
        $html = view('export-apartments', ['apartments' => $apartmentsData])->render();
        Storage::disk($adapter)->makeDirectory('temp');
        $fileName = "$apartmentCodes[0].pdf";
        $filePath = "temp/$fileName";
        Browsershot::html($html)->waitForSelector('ymaps')->waitUntilNetworkIdle()->paperSize(600, 840, 'px')->savePdf("$filePath");

        return [$filePath, $fileName];
    }

    public function getFavoriteApartments()
    {
        return $this->getApartmentsData($this->favoritesService->getFavoritePlanOfferIds());
    }

    public function getFavoriteBuildings()
    {
        return $this->getComplexesData($this->favoritesService->getFavoriteBuildingCodes());
    }

    public function getApartmentsData(array $offerIds): array
    {
        $apartments = Apartment::whereIn('offer_id', $offerIds)->get();
        $groupedApartments = [];

        foreach ($apartments as $i => &$apartment) {
            unset($apartments[$i]);
            $renovations = Renovation::where('offer_id', $apartment->offer_id)->first();

            if ($renovations != []) {
                $parsed = json_decode($renovations);
                $renovation = $parsed->renovation_url;
            } else {
                $renovation = '';
            }

            $apartment->setAttribute('renovation_url', $renovation);
            $apartment->setAttribute('displayPrice', PriceTextFormatter::priceToText($apartment->price, ' ', ' ₽', 1));
            $group = [ApartmentResource::make($apartment)->toArray(new Request())];

            foreach ($apartments as $j => $currAparment) {
                if ($currAparment->complex_id == $apartment->complex_id) {
                    $group[] = ApartmentResource::make($currAparment)->toArray(new Request());
                    unset($apartments[$j]);
                }
            }

            $hasApartmentGroup = false;

            foreach ($groupedApartments as $apartmentGroup) {
                if ($apartmentGroup['complex_id'] == $apartment->complex_id) {
                    $hasApartmentGroup = true;
                }
            }

            if (! $hasApartmentGroup) {
                $groupedApartments[] = ['complex_id' => $apartment->complex_id, 'apartments' => $group, 'complex_data' => []];
            }
        }

        foreach ($groupedApartments as $j => $group) {
            $complex = ResidentialComplex::where('id', $group['complex_id'])->first();

            if ($complex->apartments()->get()->count() == 0) {
                continue;
            }

            $complexData = ResidentialComplexResource::make($complex)->toArray(new Request());
            $complexData['description'] = $this->shortenDescription($complexData['description']);
            $group['complex_data'] = $complexData;
            $groupedApartments[$j] = $group;
        }

        return $groupedApartments;
    }

    public function getComplexesData(array $buildingCodes): array
    {
        $complexesData = [];

        foreach ($buildingCodes as $code) {
            $complex = ResidentialComplex::where('code', $code)->first();

            if ($complex->apartments()->get()->count() == 0) {
                continue;
            }

            $complexData = ResidentialComplexResource::make($complex)->toArray(new Request());
            $complexData['description'] = $this->shortenDescription($complexData['description']);
            $complexesWithApatrments = ResidentialComplexCardResource::make($complex)->toArray(new Request());
            $complexesData[] = [
                'complex_data' => $complexData,
                'stats' => $complexesWithApatrments['apartmentSpecifics']
            ];
        }

        return $complexesData;
    }

    private function shortenDescription(string $description)
    {
        $maxSymbols = 1300;
        $descriptionSplit = explode('. ', $description);
        $description = [];

        foreach ($descriptionSplit as $line) {
            if ((strlen(implode('. ', $description)) + strlen($line) < $maxSymbols)) {
                $description[] = $line;
            }
        }

        return implode('. ', $description) . '.';
    }
}
