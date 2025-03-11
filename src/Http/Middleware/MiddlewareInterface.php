<?php

namespace Mlab\Webhook\Http\Middleware;

use Mlab\Webhook\Entities\Http\HttpRequest;

interface MiddlewareInterface
{
    public function handle(HttpRequest $request, callable $next);
}