<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Jobs\FeedSynchronizationQueue;
use App\Models\Apartment;
use App\Models\Builder;
use App\Models\Journal;
use App\Models\ResidentialComplex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class FeedFormController extends Controller
{
    public function synchronizeFeed(Request $request)
    {
        if ($request->hasFile('file')) {
            $fileData = $request->file('file');
            if ($fileData->getError() === UPLOAD_ERR_INI_SIZE) {
                return response()->json(['error' => 'Файл слишком большой'], 413);
            }
            elseif (!$fileData->isValid()) {
                return response()->json(['error' => 'Ошибка при загрузке файла'], 500);
            }
            else {

                $fileDetail = [
                    'name'          => $fileData->hashName(),
                    'originalName'  => $fileData->getClientOriginalName(),
                    'extension'     => $fileData->extension(),
                    'size'          => $fileData->getSize(),
                    'mime'          => $fileData->getMimeType(),
                ];
                $fileName = $fileData->getClientOriginalName();
                $fileData->storeAs('temp-feed', $fileName, 'public');

                Artisan::call(
                    'app:loading-feed-from-trend-agent-command', [
                    'city' => $request->input('city'),
                    'fileName' => explode('.', $fileDetail['originalName'])[0],
                    'extension' => $fileDetail['extension']
                ]);
                // Запуск обновления кэша (ЖК, Квартиры)
                Artisan::call('app:update-cache-residential-complexes-command');
                Artisan::call('app:update-cache-apartments-command');
                return response()->json([]);
            }
        }
    }

    public function destroyFeedData(Request $request)
    {
        $city = $request->input('city');
        $builderIds = Builder::where('city', $city)->pluck('id');
        $complexIds = ResidentialComplex::whereIn('builder', $builderIds)->pluck('id');

        Apartment::whereIn('complex_id', $complexIds)
            ->whereNotNull('key')
            ->where('key', '<>', '')
            ->where('feed_source', 'TrendAgent')
            ->delete();

        return redirect()->route('admin.home')->with('success', 'Данные успешно удалены');
    }

    public function destroyJournal(Request $request)
    {
        $journalId = $request->input('journalId');
        Journal::where($journalId)->delete();
        return redirect()->route('admin.home')->with('success', 'Данные успешно удалены');
    }
}
