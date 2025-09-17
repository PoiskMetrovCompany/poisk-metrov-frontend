<?php

namespace App\Scrapper\TrendAgentScrapper\Queue;

use App\Core\Interfaces\Scrapper\TrendAgent\QueueProcessorInterface;
use App\Jobs\TrendAgent\ProcessApartmentsChunk;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Exception;

class RabbitMQQueueProcessor implements QueueProcessorInterface
{
    private ?AMQPStreamConnection $connection = null;
    private $channel = null; // Изменение: канал теперь является свойством класса
    private const EXCHANGE_NAME = 'trend_agent_exchange';
    private const APARTMENTS_QUEUE = 'trend_agent.apartments';
    private const COMPLEXES_QUEUE = 'trend_agent.complexes';
    private const BUILDERS_QUEUE = 'trend_agent.builders';
    private const LOCATIONS_QUEUE = 'trend_agent.locations';
    private const BUILDINGS_QUEUE = 'trend_agent.buildings';

    public function __construct()
    {
    }

    private function setupExchangesAndQueues(): void
    {
        try {
            if ($this->channel === null) {
                 $this->ensureConnection(); // Убеждаемся, что соединение и канал установлены
            }

            $this->channel->exchange_declare( // Изменение: используем $this->channel
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
                $this->channel->queue_declare( // Изменение: используем $this->channel
                    $queueName,
                    false, true, false, false
                );

                $this->channel->queue_bind( // Изменение: используем $this->channel
                    $queueName,
                    self::EXCHANGE_NAME,
                    $routingKey
                );
            }
        } catch (Exception $e) {
            Log::error("Failed to setup RabbitMQ exchanges and queues: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Перебрасываем исключение, так как без этого работать не сможем
        }
    }

    public function addToQueue(array $data, string $type, array $metadata = []): void
    {
        $this->ensureConnection();

        $jobClass = $this->getJobClassForQueue($this->getQueueNameByType($type));
        if (!$jobClass) {
            Log::warning('No job class found for type: ' . $type);
            return;
        }

        $parameterName = $this->getParameterNameForQueue($this->getQueueNameByType($type));

        $chunks = array_chunk($data, 1000);

        foreach ($chunks as $chunkIndex => $chunk) {
            $chunkMetadata = array_merge($metadata, [
                'type' => $type,
                'chunk_index' => $chunkIndex,
                'total_chunks' => count($chunks),
                'timestamp' => now()->toISOString()
            ]);

            $job = new $jobClass([
                $parameterName => $chunk
            ], $chunkMetadata);

            try {
                Queue::push($job);
            } catch (Exception $e) {
                Log::error('Failed to push job to queue', [
                    'job' => $jobClass,
                    'error' => $e->getMessage(),
                    'metadata' => $chunkMetadata,
                ]);
            }
        }
    }

    public function processQueue(string $type): void
    {
        $this->ensureConnection();
        // Изменение: channel теперь является свойством класса, не создаем новый
        if ($this->channel === null) {
            throw new Exception("RabbitMQ channel not initialized.");
        }

        $this->channel->basic_consume( // Изменение: используем $this->channel
            self::APARTMENTS_QUEUE,
            '',
            false, false, false, false,
            function (AMQPMessage $message) use ($type) {
                $this->processMessage($message, $type);
            }
        );

        while ($this->channel->is_consuming()) { // Изменение: используем $this->channel
            $this->channel->wait();
        }

        // Изменение: канал не закрывается здесь
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
            try {
                $this->connection = new AMQPStreamConnection(
                    config('queue.connections.rabbitmq.host', env('RABBITMQ_HOST', 'poisk-metrov_rabbitmq')),
                    config('queue.connections.rabbitmq.port', env('RABBITMQ_PORT_CLIENT', 5672)),
                    config('queue.connections.rabbitmq.username', env('RABBITMQ_USER', 'raptor')),
                    config('queue.connections.rabbitmq.password', env('RABBITMQ_PASSWORD', 'lama22')),
                    config('queue.connections.rabbitmq.vhost', env('RABBITMQ_VHOST', '/'))
                );
                $this->channel = $this->connection->channel(); // Изменение: создаем канал при установлении соединения
                $this->setupExchangesAndQueues(); // Изменение: вызываем setupExchangesAndQueues здесь
            } catch (Exception $e) {
                Log::error("Failed to connect to RabbitMQ: " . $e->getMessage(), [
                    'host' => config('queue.connections.rabbitmq.host', env('RABBITMQ_HOST', 'poisk-metrov_rabbitmq')),
                    'port' => config('queue.connections.rabbitmq.port', env('RABBITMQ_PORT_CLIENT', 5672)),
                    'user' => config('queue.connections.rabbitmq.username', env('RABBITMQ_USER', 'raptor')),
                    'vhost' => config('queue.connections.rabbitmq.vhost', env('RABBITMQ_VHOST', '/')),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e; // Перебрасываем исключение, чтобы остановить процесс, если нет подключения
            }
        }
    }

    public function processMessages(string $queueName, int $limit = 10): int
    {
        $this->ensureConnection();
        // Изменение: channel теперь является свойством класса, не создаем новый
        if ($this->channel === null) {
            throw new Exception("RabbitMQ channel not initialized.");
        }
        
        $processed = 0;
        
        try {
            $this->channel->queue_declare($queueName, false, true, false, false); // Изменение: используем $this->channel
            
            while ($processed < $limit) {
                $message = $this->channel->basic_get($queueName, true); // Изменение: используем $this->channel
                
                if (!$message) {
                    break;
                }
                
                $this->processQueueMessage($message, $queueName);
                $processed++;
            }
            
        } catch (\Exception $e) {
            throw $e;
        } finally {
            // Изменение: канал не закрывается здесь
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

    private function getQueueNameByType(string $type): string
    {
        return match ($type) {
            'apartments' => self::APARTMENTS_QUEUE,
            'blocks', 'complexes' => self::COMPLEXES_QUEUE,
            'builders' => self::BUILDERS_QUEUE,
            'regions', 'subways' => self::LOCATIONS_QUEUE,
            'buildings' => self::BUILDINGS_QUEUE,
            default => self::APARTMENTS_QUEUE,
        };
    }

    private function getParameterNameForQueue(string $queueName): string
    {
        return match ($queueName) {
            self::APARTMENTS_QUEUE => 'apartments',
            self::LOCATIONS_QUEUE => 'locations',
            default => 'apartments'
        };
    }

    private function getJobClassForQueue(string $queueName): ?string
    {
        return match ($queueName) {
            self::APARTMENTS_QUEUE => \App\Jobs\TrendAgent\ProcessApartmentsChunk::class,
            self::LOCATIONS_QUEUE => \App\Jobs\TrendAgent\ProcessLocationsChunk::class,
            default => null
        };
    }

    public function getQueueStatus(): array
    {
        $this->ensureConnection();
        // Изменение: channel теперь является свойством класса, не создаем новый
        if ($this->channel === null) {
            throw new Exception("RabbitMQ channel not initialized.");
        }
        $status = [];

        $queues = [
            self::APARTMENTS_QUEUE,
            self::COMPLEXES_QUEUE,
            self::BUILDERS_QUEUE,
            self::LOCATIONS_QUEUE,
            self::BUILDINGS_QUEUE
        ];

        foreach ($queues as $queueName) {
            try {
                list($queue, $messageCount, $consumerCount) = $this->channel->queue_declare($queueName, false, true, false, false);
                $status[$queueName] = [
                    'message_count' => $messageCount,
                    'consumer_count' => $consumerCount,
                ];
            } catch (Exception $e) {
                $status[$queueName] = [
                    'message_count' => 'Error: ' . $e->getMessage(),
                    'consumer_count' => 'Error',
                ];
            }
        }

        // Изменение: канал не закрывается здесь
        return $status;
    }

    public function __destruct()
    {
        if (isset($this->channel) && $this->channel->is_open()) { // Изменение: закрываем канал, если он открыт
            try {
                $this->channel->close();
            } catch (Exception $e) {
                // Игнорируем ошибки при закрытии канала, так как они могут возникнуть, если соединение уже потеряно
            }
        }
        if (isset($this->connection) && $this->connection->isConnected()) {
            try {
                $this->connection->close();
            } catch (Exception $e) {
                // Игнорируем ошибки при закрытии соединения
            }
        }
    }
}
