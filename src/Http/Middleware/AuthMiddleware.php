<?php

namespace Mlab\Webhook\Http\Middleware;

use Mlab\Webhook\Entities\Http\HttpRequest;
use Mlab\Webhook\Helpers\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(HttpRequest $request, callable $next)
    {
        $token = $request->headers()['X-API-TOKEN'] ?? '';
        
        if (empty($token) || !$this->validateToken($token[0])) {
            return Response::json(
                ['error' => 'Unauthorized'],
                401
            );
        }

        return $next($request);
    }

    private function validateToken(string $token): bool
    {
        return env('API_TOKEN') === $token;
    }
}