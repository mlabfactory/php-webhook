<?php declare(strict_types=1);
namespace Mlab\Webhook\Entities\Http;

use Psr\Http\Message\RequestInterface;

/**
 * Class HttpRequest
 * 
 * Represents an HTTP request with its associated properties and methods.
 * This class is responsible for handling incoming HTTP requests, parsing
 * request data, and providing an object-oriented interface to access
 * request information such as headers, body, method, and URL.
 * 
 * @package MLAB\PhpWebhook\Entities
 */
class HttpRequest {

    private string $uri;
    private string $method;
    private array $headers;
    private ?array $payload;
    private array $getParams;

    public function __construct(RequestInterface $request)
    {
        $this->uri = $request->getUri()->getPath();
        $this->getParams = explode('=',$request->getUri()->getQuery());
        $this->method = $request->getMethod();
        $this->headers = $request->getHeaders();
        $this->payload = json_decode($request->getBody()->getContents(), true) ?: null;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function payload(): array
    {
        return $this->payload ?? [];
    }

    /**
     * Get the value of getParams
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->getParams;
    }
}