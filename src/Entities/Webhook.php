<?php declare(strict_types=1);
namespace Mlab\Webhook\Entities;

use Mlab\Webhook\Entities\RequestEntityInterface;
use Mlab\Webhook\Services\Interfaces\Client;
use Mlab\Webhook\Services\Interfaces\ClientResponse;

/**
 * Webhook Class
 *
 * Final class representing an HTTP request implementing the RequestEntityInterface.
 * This class handles and provides access to HTTP request data.
 * 
 * @package MLAB\PhpWebhook\Entities
 */
final class Webhook implements RequestEntityInterface {

    private readonly string $path;
    private readonly array $payload;
    private readonly array $headers;
    private readonly string $method;

    private string|Client $client;

    public function __construct(string $path, array $payload, array $headers = [], string $method = 'GET')
    {
        $this->path = $path;
        $this->payload = $payload;
        $this->headers = $headers;
        $this->method = $method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function setClient(string|Client $clientName): void
    {
        $this->client = $clientName;
    }

    /**
     * Invokes the webhook.
     *
     * Triggers the webhook by sending a request to the defined endpoint
     * with the configured parameters and payload.
     *
     * @return ClientResponse The response from the webhook endpoint
     * @throws \Exception If there's an error invoking the webhook
     */
    public function invoke(): ClientResponse
    {
        if(!$this->client) {
            throw new \RuntimeException('Client not set');
        }

        if(!class_exists($this->client) || !is_subclass_of($this->client, Client::class)) {
            throw new \RuntimeException('Client class not found or not implementing Client interface');
        }

        if($this->client instanceof Client) {
            $client = $this->client;
        } else {
            $client = new $this->client();
        }
        
        return $client->request($this->method, $this->path, $this->payload);
    }


}