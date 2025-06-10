<?php

namespace App\Services\Handlers;

use App\Core\Abstracts\AbstractHandler;
use App\Core\Common\FeedFromTrendAgentFileCoREnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

final class FeedCheckHandlerService extends AbstractHandler
{
    public function handle(?array $attributes): ?array
    {
        $apartments = app(\App\Core\Interfaces\Repositories\FeedRepositoryInterface::class);
//        $feedData = [
//            'apartments' => $apartments->getFeedApartmentsData(feedKey: $attributes['_id']),
//        ];
        return parent::handle(/*[ 'feedData' => $feedData,  'fileData' => $attributes]*/ ['apartments' => $attributes]);
    }
}
