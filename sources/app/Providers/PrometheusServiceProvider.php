<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CollectorRegistry::class, function ($app) {
            return new CollectorRegistry(new Redis([
                'host'     => env('REDIS_HOST', 'localhost'),
                'port'     => env('REDIS_PORT', 6379),
                'password' => env('REDIS_PASSWORD', null),
                'timeout'  => 0.1,
                'read_timeout' => 10,
                'persistent_connections' => false
            ]));
        });
    }

    public function boot()
    {
        //
    }
}
