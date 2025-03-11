<?php declare(strict_types=1);
namespace Mlab\Webhook\Entities\Http;

use GuzzleHttp\Psr7\Response;
use Mlab\Webhook\Services\Interfaces\ClientResponse as InterfacesClientResponse;

final class HttpResponse implements InterfacesClientResponse
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;   
    }

    /**
     * Get the response status code.
     *
     * @return int The response status code
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get the response body.
     *
     * @return string The response body
     */
    public function getBody(): string
    {
        return $this->response->getBody()->getContents();
    }

    /**
     * Get the response headers.
     *
     * @return array The response headers
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * Get a specific response header.
     *
     * @param string $name The header name
     * @return string The header value
     */
    public function getHeader(string $name): string
    {
        return $this->response->getHeader($name)[0];
    }

    /**
     * Get the response error message.
     *
     * @return string The response error message
     */
    public function getError(): string
    {
        return $this->response->getReasonPhrase();
    }
}