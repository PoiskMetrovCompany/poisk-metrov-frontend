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
            'builder' => $this->readFeedBuilderFile(builderKey: $attributes['apartments']['block_builder']),
            'detail' => $this->readFeedDetailFile(detailsBlockKey: $attributes['apartments']['block_id']),
            'subway' => $this->readFeedSubwayFile(subway: $attributes['apartments']['block_subway']),
            'building' => $this->readFeedBuildingFile(buildingKey: $attributes['apartments']['building_id']),
            'buildingType' => $this->readFeedBuildingTypeFile(buildingTypeKey: $attributes['apartments']['building_type']),
            'finishing' => $this->readFeedFinishingFile(finishingKey: $attributes['apartments']['finishing']),
            'region'    => $this->readFeedRegionFile(regionKey: $attributes['apartments']['block_district']),
        ];
        $feedData = [...$attributes, ...$data];
        return parent::handle($feedData);
    }
}
