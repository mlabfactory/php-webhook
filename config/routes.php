<?php
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // send a HTTP webHook Queue
    $r->addRoute('POST', '/http[/{queueId}/{followUpUri}]', [\Mlab\Webhook\Http\Controllers\WebhookController::class, 'handle']);

    $r->addRoute('POST', '/create-queue', [\Mlab\Webhook\Http\Controllers\QueueController::class, 'handle']);
    $r->addRoute('GET', '/find-queue[/{name}]', [\Mlab\Webhook\Http\Controllers\QueueController::class, 'find']);
};
