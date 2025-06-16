<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Jobs\FeedSynchronizationQueue;
use App\Models\Journal;
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
                    'originalName'  =>  $fileData->getClientOriginalName(),
                    'extension'     => $fileData->extension(),
                    'size'          => $fileData->getSize(),
                    'mime'          => $fileData->getMimeType(),
                ];
//                $journalModel = Journal::create($fileDetail);
                $fileName = $fileData->getClientOriginalName();
                $fileData->storeAs('temp-feed', $fileName, 'public');

//                FeedSynchronizationQueue::dispatch(
//                    city:       $request->input('city'),
//                    fileName:   explode('.', $fileDetail['originalName'])[0],
//                    extension:  $fileDetail['extension']
//                );
                Artisan::call(
                    'app:loading-feed-from-trend-agent-command', [
                    'city' => $request->input('city'),
                    'fileName' => explode('.', $fileDetail['originalName'])[0],
                    'extension' => $fileDetail['extension']
                ]);
                return response()->json([]);
            }
        }
    }
}
