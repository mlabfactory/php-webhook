<?php


use Mlab\Webhook\Http\Middleware\AuthMiddleware;
use Mlab\Webhook\Http\Middleware\AuthWebhookMiddleware;

use Mlab\Webhook\Http\Middleware\Pipeline;
use Mlab\Webhook\Http\Middleware\LoggingMiddleware;

// Configura il pipeline dei middleware
$pipeline = new Pipeline();
$pipeline->pipe(new LoggingMiddleware());

// Execute the dispatcher
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);


switch($routeInfo[1][0]) {
    case 'Mlab\Webhook\Http\Controllers\WebhookController':
        $pipeline->pipe(new AuthWebhookMiddleware());
        break;
    default:
        $pipeline->pipe(new AuthMiddleware());
        break;
}