<?php

namespace App\Scrapper\TrendAgentScrapper\Queue;

use App\Core\Interfaces\Scrapper\TrendAgent\QueueProcessorInterface;
use App\Jobs\TrendAgent\ProcessApartmentsChunk;
use Illuminate\Support\Facades\Queue;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Exception;

class RabbitMQQueueProcessor implements QueueProcessorInterface
{
    private AMQPStreamConnection $connection;
    private const EXCHANGE_NAME = 'trend_agent_exchange';
    private const APARTMENTS_QUEUE = 'trend_agent.apartments';
    private const COMPLEXES_QUEUE = 'trend_agent.complexes';
    private const BUILDERS_QUEUE = 'trend_agent.builders';
    private const LOCATIONS_QUEUE = 'trend_agent.locations';
    private const BUILDINGS_QUEUE = 'trend_agent.buildings';

    public function __construct()
    {
        try {
            $this->connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.host', env('RABBITMQ_HOST', 'poisk-metrov_rabbitmq')),
                config('queue.connections.rabbitmq.port', env('RABBITMQ_PORT_CLIENT', 5672)),
                config('queue.connections.rabbitmq.username', env('RABBITMQ_USER', 'raptor')),
                config('queue.connections.rabbitmq.password', env('RABBITMQ_PASSWORD', 'lama22')),
                config('queue.connections.rabbitmq.vhost', env('RABBITMQ_VHOST', '/'))
            );

            $this->setupExchangesAndQueues();

        } catch (Exception $e) {
            throw $e;
        }
    }

    private function setupExchangesAndQueues(): void
    {
        $channel = $this->connection->channel();

        $channel->exchange_declare(
            self::EXCHANGE_NAME,
            'direct',
            false, true, false
        );

        $queues = [
            self::APARTMENTS_QUEUE => 'apartments.process',
            self::COMPLEXES_QUEUE => 'complexes.process',
            self::BUILDERS_QUEUE => 'builders.process',
            self::LOCATIONS_QUEUE => 'locations.process',
            self::BUILDINGS_QUEUE => 'buildings.process'
        ];

        foreach ($queues as $queueName => $routingKey) {
            $channel->queue_declare(
                $queueName,
                false, true, false, false
            );

            $channel->queue_bind(
                $queueName,
                self::EXCHANGE_NAME,
                $routingKey
            );
        }

        $channel->close();
    }

    public function addToQueue(array $data, string $type, array $metadata = []): void
    {
        $channel = $this->connection->channel();

        $chunks = array_chunk($data, 1000);

        foreach ($chunks as $chunkIndex => $chunk) {
            $messageData = [
                'data' => $chunk,
                'metadata' => array_merge($metadata, [
                    'type' => $type,
                    'chunk_index' => $chunkIndex,
                    'total_chunks' => count($chunks),
                    'timestamp' => now()->toISOString()
                ])
            ];

            $message = new AMQPMessage(
                json_encode($messageData),
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
            );

            $channel->basic_publish(
                $message,
                self::EXCHANGE_NAME,
                $this->getRoutingKeyByType($type)
            );
        }

        $channel->close();
    }

    public function processQueue(string $type): void
    {
        $channel = $this->connection->channel();

        $channel->basic_consume(
            self::APARTMENTS_QUEUE,
            '',
            false, false, false, false,
            function (AMQPMessage $message) use ($type) {
                $this->processMessage($message, $type);
            }
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
    }

    private function processMessage(AMQPMessage $message, string $type): void
    {
        try {
            $messageData = json_decode($message->body, true);

            Queue::push(new ProcessApartmentsChunk(
                $messageData['data'],
                $messageData['metadata']
            ));

            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        } catch (Exception $e) {
        }
    }

    private function ensureConnection(): void
    {
        if (!isset($this->connection) || !$this->connection->isConnected()) {
            $this->connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.host', env('RABBITMQ_HOST', 'poisk-metrov_rabbitmq')),
                config('queue.connections.rabbitmq.port', env('RABBITMQ_PORT_CLIENT', 5672)),
                config('queue.connections.rabbitmq.username', env('RABBITMQ_USER', 'raptor')),
                config('queue.connections.rabbitmq.password', env('RABBITMQ_PASSWORD', 'lama22')),
                config('queue.connections.rabbitmq.vhost', env('RABBITMQ_VHOST', '/'))
            );
        }
    }

    public function processMessages(string $queueName, int $limit = 10): int
    {
        $this->ensureConnection();
        $channel = $this->connection->channel();
        
        $processed = 0;
        
        try {
            $channel->queue_declare($queueName, false, true, false, false);
            
            while ($processed < $limit) {
                $message = $channel->basic_get($queueName, true);
                
                if (!$message) {
                    break;
                }
                
                $this->processQueueMessage($message, $queueName);
                $processed++;
            }
            
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $channel->close();
        }
        
        return $processed;
    }

    private function processQueueMessage($message, string $queueName): void
    {
        try {
            $data = json_decode($message->body, true);
            
            if (!$data) {
                return;
            }
            
            $jobClass = $this->getJobClassForQueue($queueName);
            
            if ($jobClass) {
                $parameterName = $this->getParameterNameForQueue($queueName);
                $job = app($jobClass, [
                    $parameterName => $data['data'] ?? $data,
                    'metadata' => $data['metadata'] ?? []
                ]);
                
                $processor = app(\App\Services\Scrapper\TrendAgent\DataProcessor::class);
                $job->handle($processor);
                
            }
            
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function getRoutingKeyByType(string $type): string
    {
        return match ($type) {
            'apartments' => 'apartments.process',
            'blocks' => 'complexes.process',
            'builders' => 'builders.process',
            'regions' => 'locations.process',
            'subways' => 'locations.process',
            'rooms' => 'rooms.process',
            'finishings' => 'finishings.process',
            'buildingtypes' => 'buildingtypes.process',
            'buildings' => 'buildings.process',
            default => 'apartments.process'
        };
    }

    private function getParameterNameForQueue(string $queueName): string
    {
        return match ($queueName) {
            self::APARTMENTS_QUEUE => 'apartments',
            self::COMPLEXES_QUEUE => 'complexes',
            self::BUILDERS_QUEUE => 'builders',
            self::LOCATIONS_QUEUE => 'locations',
            self::BUILDINGS_QUEUE => 'buildings',
            default => 'apartments'
        };
    }

    private function getJobClassForQueue(string $queueName): ?string
    {
        return match ($queueName) {
            self::APARTMENTS_QUEUE => \App\Jobs\TrendAgent\ProcessApartmentsChunk::class,
            self::COMPLEXES_QUEUE => \App\Jobs\TrendAgent\ProcessComplexesChunk::class,
            self::BUILDERS_QUEUE => \App\Jobs\TrendAgent\ProcessBuildersChunk::class,
            self::LOCATIONS_QUEUE => \App\Jobs\TrendAgent\ProcessLocationsChunk::class,
            self::BUILDINGS_QUEUE => \App\Jobs\TrendAgent\ProcessBuildingsChunk::class,
            default => null
        };
    }

    public function getQueueStatus(): array
    {
        $channel = $this->connection->channel();
        $status = [];

        $queues = [
            self::APARTMENTS_QUEUE,
            self::COMPLEXES_QUEUE,
            self::BUILDERS_QUEUE,
            self::LOCATIONS_QUEUE,
            self::BUILDINGS_QUEUE
        ];

        foreach ($queues as $queueName) {
            $queueInfo = $channel->queue_declare($queueName, true);
            $status[$queueName] = [
                'message_count' => $queueInfo[0],
                'consumer_count' => $queueInfo[1]
            ];
        }

        $channel->close();
        return $status;
    }

    public function __destruct()
    {
        if (isset($this->connection) && $this->connection->isConnected()) {
            $this->connection->close();
        }
    }
}
