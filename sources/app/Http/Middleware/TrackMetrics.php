<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Prometheus\Exception\MetricsRegistrationException;

class TrackMetrics
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $start;

        try {
            // Счетчик запросов
            $counter = app('prometheus')->getOrRegisterCounter(
                'http',
                'requests_total',
                'Total HTTP requests',
                ['method', 'route', 'status']
            );
            $counter->inc([
                $request->method(),
                $request->route()?->getName() ?: $request->path(),
                $response->getStatusCode()
            ]);

            // Гистограмма длительности запросов
            $histogram = app('prometheus')->getOrRegisterHistogram(
                'http',
                'request_duration_seconds',
                'HTTP request duration',
                ['method', 'route'],
                [0.05, 0.1, 0.25, 0.5, 1, 2.5, 5, 10]
            );
            $histogram->observe($duration, [
                $request->method(),
                $request->route()?->getName() ?: $request->path()
            ]);
        } catch (MetricsRegistrationException $e) {
            report($e);
        }

        return $response;
    }
}
