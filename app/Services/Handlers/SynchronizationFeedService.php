<?php

namespace App\Services\Handlers;

use App\Core\Abstracts\AbstractHandler;
use App\Models\Journal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

final class SynchronizationFeedService extends AbstractHandler
{

    public function handle(?array $attributes): ?array
    {
        Log::info('SynchronizationFeedService');

        $feedData = app(\App\Core\Interfaces\Repositories\FeedRepositoryInterface::class);
        $feedData->store($attributes);
        $key = Session::get('synchronizeKeySession');
        $journal = Journal::where('key', $key)->first();

        if ($journal) {
            $details = json_decode($journal->details, true);
            if (!is_array($details)) {
                $details = [];
            }

            if (!empty($details[0])) {
                $details[0]['loaded_objects'] += 1;
            } else {
                $details[] = [
                    'name' => Session::get('fileName'),
                    'found_objects' => Session::get('feedDataLength'),
                    'loaded_objects' => 1,
                ];
            }

            if ($details[0]['loaded_objects'] >= $details[0]['found_objects']) {
                $journal->update([
                    'status' => 'Загружено',
                    'details' => json_encode($details, JSON_UNESCAPED_UNICODE),
                ]);
            } else {
                $journal->update([
                    'details' => json_encode($details, JSON_UNESCAPED_UNICODE),
                ]);
            }
        }

        return parent::handle($attributes);
    }
}
