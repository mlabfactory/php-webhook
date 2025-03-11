<?php declare(strict_types=1);
namespace Mlab\Webhook\Services\Interfaces;

use Exception;

class ClientException extends Exception {

    public readonly string $path;
    public readonly string $method;
    public readonly array $payload;
    public readonly array $headers;
    
    public function __construct(string $message, string $path, string $method, array $payload, array $headers = [])
    {
        parent::__construct($message, 500);
        $this->path = $path;
        $this->method = $method;
        $this->payload = $payload;
        $this->headers = $headers;
    }
}