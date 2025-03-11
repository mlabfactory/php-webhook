<?php
require_once __DIR__ . '/connections.php';

use Illuminate\Container\Attributes\Log;
use Illuminate\Queue\Capsule\Manager as Queue;
use Psr\Log\LoggerInterface;

$events = new \Illuminate\Events\Dispatcher();

// Configura il gestore delle code
$queue = new Queue();

$manager = $queue->getQueueManager();
$manager->addConnector('database', function () use($_connection) {
    return new \Illuminate\Queue\Connectors\DatabaseConnector($_connection);
});

$queue->addConnection([
    'driver' => $_ENV['QUEUE_CONNECTION'] ?: 'database',
    'table' => 'jobs',
    'queue' => 'default',
    'attempts' => 3,
    'retry_after' => 90,
], 'default');

$queue->setAsGlobal();

// Definisci un job
class ProcessWebhook
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(array $payload)
    {
        $this->logger->info('Processing webhook', ['event' => $payload['event']]);
    }
}
