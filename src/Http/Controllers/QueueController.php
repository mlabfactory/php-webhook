<?php
namespace Mlab\Webhook\Http\Controllers;

use Mlab\Webhook\Entities\Http\HttpRequest as Request;
use Mlab\Webhook\Helpers\Response;
use Mlab\Webhook\Models\WebHook;
use Ramsey\Uuid\Uuid;

class QueueController {


    public function handle(Request $request): \Psr\Http\Message\ResponseInterface
    {
        $body = $request->payload();
        $domain = $body['domain'];

        $data = [
            'uuid' => Uuid::uuid4()->toString(),
            'domain' => $domain,
        ];
        WebHook::create($data);

        // get the webhook from the database
        $queue = WebHook::findWebHookFromUuid($data['uuid']);

        return Response::json([
            'message' => 'Queue created successfully',
            'queue' => $data,
            'hash' => generate_hash($queue)
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
        $queue = WebHook::findWebHookFromUuid($uuid);
        return Response::json($queue);
    }
}