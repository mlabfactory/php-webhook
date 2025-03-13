<?php

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Request;
use Mlab\Webhook\Entities\Http\HttpRequest;
use Mlab\Webhook\Helpers\Logger;
use Mlab\Webhook\Services\ClientService;

return [

    'app' => \DI\create(\Illuminate\Container\Container::class),

    \Illuminate\Queue\CallQueuedHandler::class => \DI\create(\Illuminate\Queue\CallQueuedHandler::class)
        ->constructor(\DI\get(\Illuminate\Contracts\Bus\Dispatcher::class)),

    // Logger
    Monolog\Logger::class => $logger,

    // RequestInterface
    RequestInterface::class => \DI\create(Request::class)
        ->constructor(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            getallheaders(),
            file_get_contents('php://input') ?: ''
        ),

    // HttpRequest
    HttpRequest::class => \DI\create(HttpRequest::class) ->constructor(\DI\get(RequestInterface::class)),
    
    //Services 
    ClientService::class => \DI\create(ClientService::class),
    
    // Logger
    Logger::class => \DI\create(Logger::class)->constructor(\DI\get(Monolog\Logger::class)),

    // Controllers
    Mlab\Webhook\Http\Controllers\WebhookController::class => \DI\create(Mlab\Webhook\Http\Controllers\WebhookController::class)
        ->constructor(\DI\get(Mlab\Webhook\Services\QueueService::class), \DI\get(ClientService::class) ),

    // Mlab\Webhook\Http\Controllers\QueueController::class => \DI\create(Mlab\Webhook\Http\Controllers\QueueController::class)
    //     ->constructor(\DI\get(Mlab\Webhook\Repositories\WebHookRepository::class)),

    // ####################################################################################################################################

    // QueueService
    Mlab\Webhook\Services\QueueService::class => \DI\create(Mlab\Webhook\Services\QueueService::class)
        ->constructor(\DI\get(\Illuminate\Queue\Capsule\Manager::class),\DI\get(ClientService::class)),

    Illuminate\Queue\Capsule\Manager::class => $queue,
    \Illuminate\Contracts\Bus\Dispatcher::class => \DI\create(\Illuminate\Bus\Dispatcher::class)->constructor(\DI\get('app')),

    // ####################################################################################################################################
    
    // Pipeline and Middleware
    \Mlab\Webhook\Http\Middleware\Pipeline::class => function($container) {
        $pipeline = new \Mlab\Webhook\Http\Middleware\Pipeline();
        $pipeline->pipe($container->get(\Mlab\Webhook\Http\Middleware\LoggingMiddleware::class))
                ->pipe($container->get(\Mlab\Webhook\Http\Middleware\AuthMiddleware::class));
        return $pipeline;
    },
    \Mlab\Webhook\Http\Middleware\LoggingMiddleware::class => \DI\create(),
    \Mlab\Webhook\Http\Middleware\AuthMiddleware::class => \DI\create(),

    // ####################################################################################################################################
    
    'mysql-service' => \DI\create(\Mlab\Webhook\Services\DbServiceConnection::class)->constructor($_connection->getDefaultConnection()),

    // Repositories MYSQL
    \Mlab\Webhook\Repositories\WebHookRepository::class => \DI\create(Mlab\Webhook\Repositories\WebHookRepository::class) ->constructor(\DI\get('mysql-service')),
    \Mlab\Webhook\Repositories\QueueRepository::class => \DI\create(\Mlab\Webhook\Repositories\QueueRepository::class)->constructor(\DI\get('mysql-service')),
        
    // Models Repository with MYSQL
    \Mlab\Webhook\Models\FailedJob::class => \DI\create(Mlab\Webhook\Models\FailedJob::class)->constructor(\DI\get(\Mlab\Webhook\Repositories\QueueRepository::class)),
    \Mlab\Webhook\Models\WebHook::class => \DI\create(Mlab\Webhook\Models\WebHook::class)->constructor(\DI\get(\Mlab\Webhook\Repositories\WebHookRepository::class)),

    // ####################################################################################################################################
    
];