<?php

namespace Mlab\Webhook\Http\Middleware;

use Mlab\Webhook\Entities\Http\HttpRequest;

class Pipeline
{
    private array $middlewares = [];

    public function pipe(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function process(HttpRequest $request, callable $handler)
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            $this->carry(),
            $handler
        );

        return $pipeline($request);
    }

    private function carry(): callable
    {
        return function ($stack, $middleware) {
            return function ($request) use ($stack, $middleware) {
                return $middleware->handle($request, $stack);
            };
        };
    }
}