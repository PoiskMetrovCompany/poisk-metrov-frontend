<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TrendAgent Scraper Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for TrendAgent scraper service including processing
    | settings, queue configuration, and caching options.
    |
    */

    'processing' => [
        'chunk_size' => env('TREND_AGENT_CHUNK_SIZE', 1000),
        'workers_count' => env('TREND_AGENT_WORKERS', 5),
        'max_retries' => env('TREND_AGENT_MAX_RETRIES', 3),
        'retry_delay' => env('TREND_AGENT_RETRY_DELAY', 60), // seconds
        'timeout' => env('TREND_AGENT_TIMEOUT', 300), // seconds
    ],

    'cache' => [
        'ttl' => env('TREND_AGENT_CACHE_TTL', 3600), // 1 hour
        'driver' => env('TREND_AGENT_CACHE_DRIVER', env('CACHE_DRIVER', 'file')),
        'prefix' => 'trend_agent',
    ],

    'queue' => [
        'connection' => env('TREND_AGENT_QUEUE_CONNECTION', 'rabbitmq'),
        'exchange' => 'trend_agent_exchange',
        'routing_keys' => [
            'apartments' => 'apartments.process',
            'complexes' => 'complexes.process',
            'builders' => 'builders.process',
            'locations' => 'locations.process',
        ],
        'queues' => [
            'apartments' => 'trend_agent.apartments',
            'complexes' => 'trend_agent.complexes',
            'builders' => 'trend_agent.builders',
            'locations' => 'trend_agent.locations',
            'dlq' => 'trend_agent.dlq',
        ],
        'priorities' => [
            'high' => 0,
            'normal' => 30,
            'low' => 120,
        ],
    ],

    'http' => [
        'user_agent' => 'TrendAgent-Scraper/1.0',
        'timeout' => env('TREND_AGENT_HTTP_TIMEOUT', 30),
        'retries' => env('TREND_AGENT_HTTP_RETRIES', 3),
        'retry_delay' => env('TREND_AGENT_HTTP_RETRY_DELAY', 5),
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
    ],

    'monitoring' => [
        'enabled' => env('TREND_AGENT_MONITORING_ENABLED', true),
        'metrics_prefix' => 'trend_agent_scraper',
        'log_channel' => env('TREND_AGENT_LOG_CHANNEL', 'daily'),
        'alerts' => [
            'max_processing_time' => env('TREND_AGENT_MAX_PROCESSING_TIME', 3600), // 1 hour
            'max_errors_threshold' => env('TREND_AGENT_MAX_ERRORS_THRESHOLD', 100),
            'alert_email' => env('TREND_AGENT_ALERT_EMAIL', null),
        ],
    ],

    'cities' => [
        'spb' => [
            'name' => 'Санкт-Петербург',
            'code' => 'spb',
            'url' => env('TREND_AGENT_SPB_URL', 'https://dataout.trendagent.ru/spb/about.json'),
            'enabled' => env('TREND_AGENT_SPB_ENABLED', true),
        ],
        'msk' => [
            'name' => 'Москва',
            'code' => 'msk',
            'url' => env('TREND_AGENT_MSK_URL', 'https://dataout.trendagent.ru/msk/about.json'),
            'enabled' => env('TREND_AGENT_MSK_ENABLED', true),
        ],
        'krd' => [
            'name' => 'Краснодар',
            'code' => 'krd',
            'url' => env('TREND_AGENT_KRD_URL', 'https://dataout.trendagent.ru/krasnodar/about.json'),
            'enabled' => env('TREND_AGENT_KRD_ENABLED', true),
        ],
        'nsk' => [
            'name' => 'Новосибирск',
            'code' => 'nsk',
            'url' => env('TREND_AGENT_NSK_URL', 'https://dataout.trendagent.ru/nsk/about.json'),
            'enabled' => env('TREND_AGENT_NSK_ENABLED', true),
        ],
        'rst' => [
            'name' => 'Ростов-на-Дону',
            'code' => 'rst',
            'url' => env('TREND_AGENT_RST_URL', 'https://dataout.trendagent.ru/rostov/about.json'),
            'enabled' => env('TREND_AGENT_RST_ENABLED', true),
        ],
        'kzn' => [
            'name' => 'Казань',
            'code' => 'kzn',
            'url' => env('TREND_AGENT_KZN_URL', 'https://dataout.trendagent.ru/kzn/about.json'),
            'enabled' => env('TREND_AGENT_KZN_ENABLED', true),
        ],
        'ekb' => [
            'name' => 'Екатеринбург',
            'code' => 'ekb',
            'url' => env('TREND_AGENT_EKB_URL', 'https://dataout.trendagent.ru/ekb/about.json'),
            'enabled' => env('TREND_AGENT_EKB_ENABLED', true),
        ],
    ],

    'feeds' => [
        'apartments' => [
            'endpoint' => 'apartments.json',
            'batch_size' => env('TREND_AGENT_APARTMENTS_BATCH_SIZE', 500),
            'priority' => 'high',
        ],
        'complexes' => [
            'endpoint' => 'blocks.json',
            'batch_size' => env('TREND_AGENT_COMPLEXES_BATCH_SIZE', 200),
            'priority' => 'high',
        ],
        'builders' => [
            'endpoint' => 'builders.json',
            'batch_size' => env('TREND_AGENT_BUILDERS_BATCH_SIZE', 100),
            'priority' => 'normal',
        ],
        'buildings' => [
            'endpoint' => 'buildings.json',
            'batch_size' => env('TREND_AGENT_BUILDINGS_BATCH_SIZE', 200),
            'priority' => 'normal',
        ],
        'locations' => [
            'endpoint' => 'regions.json',
            'batch_size' => env('TREND_AGENT_LOCATIONS_BATCH_SIZE', 50),
            'priority' => 'low',
        ],
        'subways' => [
            'endpoint' => 'subways.json',
            'batch_size' => env('TREND_AGENT_SUBWAYS_BATCH_SIZE', 100),
            'priority' => 'low',
        ],
        'finishings' => [
            'endpoint' => 'finishings.json',
            'batch_size' => env('TREND_AGENT_FINISHINGS_BATCH_SIZE', 20),
            'priority' => 'low',
        ],
        'building_types' => [
            'endpoint' => 'buildingtypes.json',
            'batch_size' => env('TREND_AGENT_BUILDING_TYPES_BATCH_SIZE', 20),
            'priority' => 'low',
        ],
    ],

    'database' => [
        'connection' => env('TREND_AGENT_DB_CONNECTION', env('DB_CONNECTION', 'mysql')),
        'chunk_size' => env('TREND_AGENT_DB_CHUNK_SIZE', 100),
        'isolation_level' => env('TREND_AGENT_DB_ISOLATION_LEVEL', 'READ_COMMITTED'),
        'lock_timeout' => env('TREND_AGENT_DB_LOCK_TIMEOUT', 30), // seconds
    ],

    'logging' => [
        'level' => env('TREND_AGENT_LOG_LEVEL', 'info'),
        'channel' => env('TREND_AGENT_LOG_CHANNEL', 'daily'),
        'max_files' => env('TREND_AGENT_LOG_MAX_FILES', 30),
        'include_request_id' => env('TREND_AGENT_LOG_REQUEST_ID', true),
        'include_session_id' => env('TREND_AGENT_LOG_SESSION_ID', true),
    ],
];
