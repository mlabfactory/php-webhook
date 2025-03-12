<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
require_once __DIR__ . '/../config/load_env.php';

// Load database connections
require_once __DIR__ . '/../config/connections.php';

use DI\ContainerBuilder;
use function FastRoute\simpleDispatcher;

// Define routes
$dispatcher = simpleDispatcher(require __DIR__ . '/../config/routes.php');

// Retrieve HTTP method and URI from the request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Load Monolog configuration
require_once __DIR__ . '/../config/monolog.php';

// Load Bernard queue configuration
require_once __DIR__ . '/../config/queue.php';

// Load container configuration
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(require __DIR__ . '/../config/di.php');
$container = $containerBuilder->build();
$container->set('container', $container);

// Facade initialization
$facade = require __DIR__ . '/../config/facade.php';
// Set up the Facade application
\Illuminate\Support\Facades\Facade::setFacadeApplication($facade);

require_once __DIR__ . '/../config/middleware.php';


try {
    switch ($routeInfo[0]) {
        case \FastRoute\Dispatcher::NOT_FOUND:
            response(404, '404 Not Found');
            break;
        case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            response(405, '405 Method Not Allowed');
            break;
        case \FastRoute\Dispatcher::FOUND:
            [$controller, $method] = $routeInfo[1];
            $controller = $container->get($controller);
            $request = $container->get(\Mlab\Webhook\Entities\Http\HttpRequest::class);
    
            $vars = $routeInfo[2];
            $response = $pipeline->process($request, function ($request) use ($controller, $method, $vars) {
                return $controller->$method($request, ...$vars);
            });
            $body = $response->getBody();
            response($response->getStatusCode(), $body);
            break;
    }
} catch (\Exception $e) {
    response(500, '500 Internal Server Error');
}
