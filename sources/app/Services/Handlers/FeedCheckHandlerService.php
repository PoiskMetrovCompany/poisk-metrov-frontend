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
        return parent::handle( ['apartments' => $attributes]);
    }
}
