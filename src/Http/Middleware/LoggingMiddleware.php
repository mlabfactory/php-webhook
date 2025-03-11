<?php

namespace Mlab\Webhook\Http\Middleware;

use Mlab\Webhook\Entities\Http\HttpRequest;
use Mlab\Webhook\Helpers\Logger;

class LoggingMiddleware implements MiddlewareInterface
{
    public function handle(HttpRequest $request, callable $next)
    {
        // Prima della richiesta
        Logger::info('Inizio elaborazione richiesta', [
            'uri' => $request->uri(),
            'method' => $request->method()
        ]);

        // Esegui la richiesta
        $response = $next($request);

        // Dopo la richiesta
        Logger::info('Fine elaborazione richiesta', [
            'uri' => $request->uri(),
            'status' => $response->getStatusCode()
        ]);

        return $response;
    }
}