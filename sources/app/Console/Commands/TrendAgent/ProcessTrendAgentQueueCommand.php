<?php

namespace App\Console\Commands\TrendAgent;

use App\Core\Interfaces\Scrapper\TrendAgent\QueueProcessorInterface;
use Illuminate\Console\Command;

class ProcessTrendAgentQueueCommand extends Command
{
    protected $signature = 'trend-agent:process-queue {--queue=trend_agent.apartments} {--limit=10}';
    protected $description = 'Process TrendAgent queue messages manually';

    public function __construct(
        private QueueProcessorInterface $queueProcessor
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $queue = $this->option('queue');
        $limit = (int) $this->option('limit');

        $this->info("Processing {$limit} messages from queue: {$queue}");

        try {
            $processed = $this->queueProcessor->processMessages($queue, $limit);
            
            $this->info("Successfully processed {$processed} messages");
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error processing queue: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
