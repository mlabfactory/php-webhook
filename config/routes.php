<?php
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // Group HTTP webhook routes with same pattern and handler
    $r->addGroup('/http[/{queueId}/{followUpUri:.*}]', function (RouteCollector $r) {
        $r->addRoute(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '', [
            \Mlab\Webhook\Http\Controllers\WebhookController::class, 
            'handle'
        ]);
    });
    
    // Queue management routes
    $r->addRoute('POST', '/create', [\Mlab\Webhook\Http\Controllers\QueueController::class, 'handle']);
    $r->addRoute('GET', '/find-queue[/{name}]', [\Mlab\Webhook\Http\Controllers\QueueController::class, 'find']);
};
