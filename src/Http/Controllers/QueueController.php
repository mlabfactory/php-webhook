<?php
namespace Mlab\Webhook\Http\Controllers;

use Mlab\Webhook\Entities\Http\HttpRequest as Request;
use Mdf\JsonStorage\Service\DbService;
use Mlab\Webhook\Helpers\Response;
use Mlab\Webhook\Models\QueueMapperModel;
use Mlab\Webhook\Repositories\WebHookRepository;

class QueueController {

    private WebHookRepository $repository;

    public function __construct(WebHookRepository $repository)
    {
        $this->repository = $repository;
    }


    public function handle(Request $request): \Psr\Http\Message\ResponseInterface
    {
        $body = $request->payload();
        $domain = $body['domain'];

        $service = $this->repository;
        $queue = QueueMapperModel::create(
            [
                'domain' => $domain,
            ]
        );
        $service->insert(
            $queue
        );

        return Response::json([
            'message' => 'Queue created successfully',
            'queue' => $queue->__toArray(),
            'hash' => generate_hash($queue->__toArray())
        ]);

    }

    /**
     * Find a queue by name.
     *
     * @param string $name The name of the queue to find
     * @return \Psr\Http\Message\ResponseInterface The HTTP response interface
     */
    public function find(Request $request, string $uuid): \Psr\Http\Message\ResponseInterface
    {
        $db = $this->repository;
        $queue = $db->findWebHookFromUuid($uuid);

        return Response::json($queue);
    }
}