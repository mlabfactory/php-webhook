<?php

namespace Mlab\Webhook\Http\Middleware;

use Mlab\Webhook\Entities\Http\HttpRequest;
use Mlab\Webhook\Helpers\Response;
use Mlab\Webhook\Models\WebHook;

class AuthWebhookMiddleware implements MiddlewareInterface
{
    public function handle(HttpRequest $request, callable $next)
    {
        $token = $request->headers()['X-HASH'] ?? '';

        try {
            $queueUuid = explode('/', $request->uri())[2];
            $hash = $this->generateHash($queueUuid);
        } catch (\Exception $e) {
            return Response::json(
                ['error' => $e->getMessage()],
                404
            );
        }

        if (empty($token) || $hash !== $token[0]) {
            return Response::json(
                ['error' => 'Unauthorized'],
                401
            );
        }

        return $next($request);
    }

    private function generateHash(string $uuid): string
    {
        $queue = WebHook::findWebHookFromUuid($uuid);
        if($queue === null) {
            throw new \Exception('Queue not found', 404);
        }
        
        return generate_hash($queue);
    }
}