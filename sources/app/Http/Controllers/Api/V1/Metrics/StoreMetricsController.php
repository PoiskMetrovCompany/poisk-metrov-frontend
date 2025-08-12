<?php

namespace App\Http\Controllers\Api\V1\Metrics;

use App\Core\Abstracts\AbstractOperations;
use App\Models\Metrics;
use Illuminate\Http\Request;

class StoreMetricsController extends AbstractOperations
{
    public function __construct()
    {

    }

    public function __invoke(Request $request) 
    {
        
    }

    public function getEntityClass(): string
    {
        return Metrics::class;
    }

    public function getResourceClass(): string
    {
        return ApartmentCollection::class;
    }
}
