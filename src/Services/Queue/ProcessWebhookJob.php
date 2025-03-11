<?php
namespace Mlab\Webhook\Services\Queue;

use Illuminate\Contracts\Queue\ShouldQueue;
use Mlab\Webhook\Entities\RequestEntityInterface;
use Mlab\Webhook\Services\Interfaces\ClientResponse;

class ProcessWebhookJob implements ShouldQueue
{
    protected $webhook;

    public function __construct(RequestEntityInterface $webhook)
    {
        $this->webhook = $webhook;
    }

    public function handle(): ClientResponse
    {
        return $this->webhook->invoke();
    }
}