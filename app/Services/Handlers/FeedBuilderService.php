<?php

namespace App\Services\Handlers;

use App\Core\Abstracts\AbstractHandler;
use Illuminate\Support\Facades\Log;

final class FeedBuilderService extends AbstractHandler
{
    public function handle(?array $attributes): ?array
    {
        Log::info('FeedBuilderService');
        $data = [
            'builder' => $this->readFeedBuilderFile(builderKey: $attributes['fileData']['block_builder']),
            'detail' => $this->readFeedDetailFile(detailsBlockKey: $attributes['fileData']['block_id']),
            'subway' => $this->readFeedSubwayFile(subway: $attributes['fileData']['block_subway']),
            'building' => $this->readFeedBuildingFile(buildingKey: $attributes['fileData']['building_id']),
            'buildingType' => $this->readFeedBuildingTypeFile(buildingTypeKey: $attributes['fileData']['building_type']),
            'finishing' => $this->readFeedFinishingFile(finishingKey: $attributes['fileData']['finishing']),
            'region'    => $this->readFeedRegionFile(regionKey: $attributes['fileData']['district']),
        ];
        $feedData = [...$attributes['feedData'], ...$data];
        return parent::handle($feedData);
    }
}
