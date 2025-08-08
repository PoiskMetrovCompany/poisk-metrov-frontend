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
        $feedData = app(\App\Core\Interfaces\Repositories\FeedRepositoryInterface::class);
        $feedData->store($attributes);
        $key = Session::get('synchronizeKeySession');
        $journal = Journal::where('key', $key)->first();

        if ($journal) {
            $journal->update([
                'status' => 'Загружено',
                'name' => Session::get('fileName'),
                'loaded_objects' => ($journal->loaded_objects + 1),
            ]);
        }

        return parent::handle($attributes);
    }
}
