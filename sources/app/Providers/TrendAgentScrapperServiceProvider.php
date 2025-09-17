<?php

namespace App\Providers;

use App\Services\Scrapper\TrendAgent\TrendAgentScrapperService;
use App\Services\Scrapper\TrendAgent\DataProcessor;
use App\Scrapper\TrendAgentScrapper\CachedDownloadManager;
use App\Scrapper\TrendAgentScrapper\Queue\RabbitMQQueueProcessor;
use App\Scrapper\TrendAgentScrapper\TrendAgentUrlManager;
use App\Core\Interfaces\Scrapper\TrendAgent\QueueProcessorInterface;
use Illuminate\Support\ServiceProvider;

class TrendAgentScrapperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TrendAgentScrapperService::class, function ($app) {
            return new TrendAgentScrapperService(
                $app->make(CachedDownloadManager::class),
                $app->make(QueueProcessorInterface::class),
                $app->make(TrendAgentUrlManager::class),
                $app->make(DataProcessor::class)
            );
        });

        $this->app->singleton(CachedDownloadManager::class, function ($app) {
            return new CachedDownloadManager();
        });

        $this->app->singleton(RabbitMQQueueProcessor::class, function ($app) {
            return new RabbitMQQueueProcessor();
        });

        $this->app->bind(QueueProcessorInterface::class, RabbitMQQueueProcessor::class);

        $this->app->singleton(TrendAgentUrlManager::class, function ($app) {
            return new TrendAgentUrlManager();
        });

        $this->app->singleton(DataProcessor::class, function ($app) {
            return new DataProcessor(
                $app->make(\App\Repositories\ApartmentRepository::class),
                $app->make(\App\Repositories\BuilderRepository::class),
                $app->make(\App\Repositories\ResidentialComplexRepository::class),
                $app->make(\App\Repositories\LocationRepository::class),
                $app->make(\App\Core\Interfaces\Services\TrendAgentMappingServiceInterface::class)
            );
        });

        $this->mergeConfigFrom(
            __DIR__.'/../../config/trend-agent.php',
            'trend-agent'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/trend-agent.php' => config_path('trend-agent.php'),
            ], 'trend-agent-config');

        $this->commands([
            \App\Console\Commands\TrendAgent\TrendAgentScrapperCommand::class,
            \App\Console\Commands\TrendAgent\TestTrendAgentScrapperCommand::class,
            \App\Console\Commands\TrendAgent\ProcessTrendAgentQueueCommand::class,
            \App\Console\Commands\TrendAgent\LinkApartmentsToComplexesCommand::class,
        ]);
        }

        $this->configureQueues();
    }


    private function configureQueues(): void
    {
        config([
            'queue.connections.trend_agent_rabbitmq' => [
                'driver' => 'rabbitmq',
                'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                'port' => env('RABBITMQ_PORT', 5672),
                'vhost' => env('RABBITMQ_VHOST', '/'),
                'login' => env('RABBITMQ_USER', 'guest'),
                'password' => env('RABBITMQ_PASSWORD', 'guest'),
                'queue' => env('RABBITMQ_QUEUE', 'default'),
                'exchange_declare' => true,
                'queue_declare_bind' => true,
                'consumer_tag' => 'trend_agent_consumer',
                'exchange' => config('trend-agent.queue.exchange'),
                'exchange_type' => 'direct',
                'exchange_routing_key' => '',
            ]
        ]);

        config([
            'queue.connections.trend-agent-apartments' => [
                'driver' => 'rabbitmq',
                'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                'port' => env('RABBITMQ_PORT', 5672),
                'vhost' => env('RABBITMQ_VHOST', '/'),
                'login' => env('RABBITMQ_USER', 'guest'),
                'password' => env('RABBITMQ_PASSWORD', 'guest'),
                'queue' => config('trend-agent.queue.queues.apartments'),
                'exchange' => config('trend-agent.queue.exchange'),
                'exchange_type' => 'direct',
                'exchange_routing_key' => config('trend-agent.queue.routing_keys.apartments'),
            ],
            'queue.connections.trend-agent-complexes' => [
                'driver' => 'rabbitmq',
                'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                'port' => env('RABBITMQ_PORT', 5672),
                'vhost' => env('RABBITMQ_VHOST', '/'),
                'login' => env('RABBITMQ_USER', 'guest'),
                'password' => env('RABBITMQ_PASSWORD', 'guest'),
                'queue' => config('trend-agent.queue.queues.complexes'),
                'exchange' => config('trend-agent.queue.exchange'),
                'exchange_type' => 'direct',
                'exchange_routing_key' => config('trend-agent.queue.routing_keys.complexes'),
            ],
            'queue.connections.trend-agent-builders' => [
                'driver' => 'rabbitmq',
                'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                'port' => env('RABBITMQ_PORT', 5672),
                'vhost' => env('RABBITMQ_VHOST', '/'),
                'login' => env('RABBITMQ_USER', 'guest'),
                'password' => env('RABBITMQ_PASSWORD', 'guest'),
                'queue' => config('trend-agent.queue.queues.builders'),
                'exchange' => config('trend-agent.queue.exchange'),
                'exchange_type' => 'direct',
                'exchange_routing_key' => config('trend-agent.queue.routing_keys.builders'),
            ],
            'queue.connections.trend-agent-locations' => [
                'driver' => 'rabbitmq',
                'host' => env('RABBITMQ_HOST', '127.0.0.1'),
                'port' => env('RABBITMQ_PORT', 5672),
                'vhost' => env('RABBITMQ_VHOST', '/'),
                'login' => env('RABBITMQ_USER', 'guest'),
                'password' => env('RABBITMQ_PASSWORD', 'guest'),
                'queue' => config('trend-agent.queue.queues.locations'),
                'exchange' => config('trend-agent.queue.exchange'),
                'exchange_type' => 'direct',
                'exchange_routing_key' => config('trend-agent.queue.routing_keys.locations'),
            ],
        ]);
    }
}
