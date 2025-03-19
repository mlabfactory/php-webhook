<?php

namespace Mlab\Webhook\Http\Controllers;

use Mlab\Webhook\Helpers\Logger;
use Mlab\Webhook\Models\WebHook as WebHookModel;
use Mlab\Webhook\Entities\Webhook;
use Mlab\Webhook\Helpers\Response;
use Mlab\Webhook\Services\QueueService;
use Psr\Http\Message\ResponseInterface;
use Mlab\Webhook\Entities\Http\HttpRequest;
use Mlab\Webhook\Services\Interfaces\Client;
use Mlab\Webhook\Repositories\WebHookRepository;

class WebhookController extends Controller
{

    protected QueueService $queueService;
    protected Client $client;

    public function __construct(QueueService $queueService, Client $client)
    {
        parent::__construct($queueService, $client);
    }

    /**
     * Handles an incoming webhook HTTP request.
     * 
     * This method processes HTTP webhook requests and returns an array response.
     * 
     * @param HttpRequest $request The incoming HTTP request instance
     * @param string $queueId The queue ID
     * @param string $followUpUri The follow-up URI
     * 
     * @return ResponseInterface The response data as an associative array
     */
    public function handle(HttpRequest $request, string $queueId, string $followUpUri): ResponseInterface
    {
        $queue = $this->getHttpQueueDomain($queueId);
        if(is_null($queue)) {
            return Response::json(["message"=>"no domain found"],404);
        }

        try {
            $webhook = new Webhook(
                "$queue/$followUpUri",
                $request->payload(),
                $request->headers(),
                $request->method()
            );
            
            $webhook->setClient($this->client::class);

            if ($this->queueService->enqueue($webhook)) {
                return Response::json(['status' => 'success', 'message' => 'Webhook accodato con successo']);
            }

            return Response::json(['status' => 'error', 'message' => 'Errore nell\'accodamento del webhook'], 400);
        } catch (\Exception $e) {
            Logger::error('Errore nella gestione del webhook', [
                'error' => $e->getMessage()
            ]);

            return Response::json(['status' => 'error', 'message' => 'Errore interno del server'],500);
        }
    }

    /**
     * Get the HTTP domain for a specific queue.
     *
     * This method retrieves the HTTP domain information associated with a queue identified by its UUID.
     * It's used to determine where webhook requests should be directed.
     *
     * @param string $queueUuid The UUID of the queue to retrieve the domain for
     * @return string With the quque domain follow up uri or null
     */
    protected function getHttpQueueDomain(string $queueUuid): ?string
    {
        $queue = WebHookModel::findWebHookFromUuid($queueUuid);

        if (empty($queue)) {
            return null;
        }

        return $queue['domain'];
    }
}