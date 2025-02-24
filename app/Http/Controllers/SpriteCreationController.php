<?php

namespace App\Http\Controllers;

use App\Models\SpriteImagePosition;
use App\Models\ResidentialComplex;
use App\Services\TextService;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SpriteCreationController extends Controller
{
    public function __construct(protected TextService $textService)
    {
    }


    public function combineImages(string $firstImagePath, string $secondImagePath, string $outputPath, int $buildingId)
    {
        /* Get images dimensions */
        $size1 = getimagesize($firstImagePath);
        $size2 = getimagesize($secondImagePath);

        $image1 = file_get_contents($firstImagePath);

        if (! $image1) {
            return;
        }

        $image2 = file_get_contents($secondImagePath);

        if (! $image2) {
            return;
        }

        /* Load the two existing images */
        $im1 = imagecreatefromstring($image1);
        $im2 = imagecreatefromstring($image2);

        $desiredImageHeight = 330;

        $firstSizeX = $size1[0];
        $firstSizeY = $size1[1];
        $secondSizeX = $size2[0];
        $secondSizeY = $size2[1];
        $desiredFirstSizeX = $size1[0] * ($desiredImageHeight / $size1[1]);
        $desiredFirstSizeY = $desiredImageHeight;
        $desiredSecondSizeX = $size2[0] * ($desiredImageHeight / $size2[1]);
        $desiredSecondSizeY = $desiredImageHeight;

        /* Create the new image, width is combined but height is the max height of either image */
        $newImage = imagecreatetruecolor($desiredFirstSizeX + $desiredSecondSizeX, $desiredFirstSizeY);

        /* Merge the two images into the new one */
        imagecopyresampled($newImage, $im1, 0, 0, 0, 0, $desiredFirstSizeX, $desiredFirstSizeY, $firstSizeX, $firstSizeY);
        imagecopyresampled($newImage, $im2, $desiredFirstSizeX, 0, 0, 0, $desiredSecondSizeX, $desiredSecondSizeY, $secondSizeX, $secondSizeY);
        $firstSizeX = $desiredFirstSizeX;
        $firstSizeY = $desiredFirstSizeY;
        $secondSizeX = $desiredSecondSizeX;
        $secondSizeY = $desiredSecondSizeY;

        $firstExistingPosition = SpriteImagePosition::where('filepath', '=', $firstImagePath)->first();

        if ($firstExistingPosition != null) {
            $firstExistingPosition->delete();
        }

        if ($firstImagePath != $outputPath) {
            SpriteImagePosition::create([
                'building_id' => $buildingId,
                'filepath' => $firstImagePath,
                'x' => 0,
                'y' => 0,
                'size_x' => $firstSizeX,
                'size_y' => $firstSizeY
            ]);
        }

        SpriteImagePosition::create([
            'building_id' => $buildingId,
            'filepath' => $secondImagePath,
            'x' => imagesx($newImage) - $secondSizeX,
            'y' => 0,
            'size_x' => $secondSizeX,
            'size_y' => $secondSizeY
        ]);

        imagejpeg($newImage, $outputPath);
        imagedestroy($newImage);
    }

    public function downloadTempPictures(): array
    {
        $publicPath = public_path();
        $tempSpritePath = 'tempsprites';
        Storage::deleteDirectory($tempSpritePath);
        $buildings = ResidentialComplex::all();
        $buildingUrls = [];

        if (! Storage::directoryExists($tempSpritePath)) {
            Storage::makeDirectory($tempSpritePath);
        }

        foreach ($buildings as $building) {
            $images = $building->getGalleryImages();

            if (count($images) == 0) {
                continue;
            }

            $buildingUrls[$building->code] = [];

            foreach ($images as &$image) {
                if (Str::startsWith($image, 'galleries')) {
                    $image = "{$publicPath}/{$image}";
                }

                //На всякий случай подчищаем параметры ссылок из ссылок на картинки, иначе больше вероятность что картинка не загрузится
                $image = $this->textService->cleanupNmarketImageURL($image);
            }

            for ($i = 0; $i < count($images) && $i < 5; $i++) {
                $downloadedImage = null;
                $extension = 'jpg';

                if (Str::startsWith($images[$i], 'galleries')) {
                    $extension = $this->textService->getFileExtension($images[$i]);
                }

                for ($j = 0; $j < 5 && $downloadedImage == null; $j++) {
                    try {
                        $downloadedImage = file_get_contents($images[$i]);
                    } catch (Exception $e) {
                        print ($e->getMessage() . PHP_EOL);
                    }
                }

                if ($downloadedImage != null) {
                    $storagePath = "$tempSpritePath/{$building->code}_$i.$extension";
                    Storage::put($storagePath, $downloadedImage);
                    $buildingUrls[$building->code][] = Storage::path("$storagePath");
                }
            }
        }

        return $buildingUrls;
    }

    public function createBuildingSprites()
    {
        $imagesForBuildings = $this->downloadTempPictures();
        $tempSpritePath = 'tempsprites';
        $storagePublicPath = Storage::path('public');
        $specificOutputPath = "{$storagePublicPath}/sprites";
        SpriteImagePosition::truncate();

        if (! file_exists($specificOutputPath)) {
            mkdir($specificOutputPath);
        }

        foreach ($imagesForBuildings as $code => $images) {
            if (! count($images)) {
                continue;
            }

            $building = ResidentialComplex::where("code", $code)->first();
            $outputFileName = "{$specificOutputPath}/{$code}.jpg";

            for ($i = 0; $i < count($images) - 1 && $i < 5; $i++) {
                $this->combineImages($i == 0 ? $images[$i] : $outputFileName, $images[$i + 1], $outputFileName, $building->id);
            }
        }

        Storage::deleteDirectory($tempSpritePath);
    }
}
