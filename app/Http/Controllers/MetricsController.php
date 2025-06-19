<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

class MetricsController extends Controller
{
    public function __invoke(CollectorRegistry $registry)
    {
        $renderer = new RenderTextFormat();
        $metrics = $renderer->render($registry->getMetricFamilySamples());

        return response($metrics)
            ->header('Content-Type', 'text/plain; version=0.0.4');
    }
}
