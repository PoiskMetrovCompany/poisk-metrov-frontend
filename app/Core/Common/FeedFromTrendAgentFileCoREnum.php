<?php

namespace App\Core\Common;

enum FeedFromTrendAgentFileCoREnum: string
{
    case APARTMENTS = 'apartments.json';
    case BUILDER = 'builders.json';
    case BLOCKS = 'blocks.json';
    case SUBWAYS  = 'subways.json';
    case REGIONS = 'regions.json';
    case BUILDINGS = 'buildings.json';
    case BUILDING_TYPES = 'buildingtypes.json';
    case FINISHINGS = 'finishings.json';
}
