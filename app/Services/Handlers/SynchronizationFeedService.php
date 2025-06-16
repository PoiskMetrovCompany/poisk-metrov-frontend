<?php

namespace App\Services\Handlers;

use App\Core\Abstracts\AbstractHandler;
use Illuminate\Support\Facades\Log;

final class SynchronizationFeedService extends AbstractHandler
{

    public function handle(?array $attributes): ?array
    {
        Log::info('SynchronizationFeedService');
        $feedData = app(\App\Core\Interfaces\Repositories\FeedRepositoryInterface::class);
        $feedData->store($attributes);
        return parent::handle($attributes);
    }
}
