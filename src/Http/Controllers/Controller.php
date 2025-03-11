<?php declare(strict_types=1);

namespace Mlab\Webhook\Http\Controllers;

use Mlab\Webhook\Services\QueueService;
use Mlab\Webhook\Services\Interfaces\Client;

class Controller {

    protected QueueService $queueService;
    protected Client $client;

    public function __construct(QueueService $queueService, Client $client)
    {
        $this->queueService = $queueService;
        $this->client = $client;
    }
        
}