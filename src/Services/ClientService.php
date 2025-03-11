<?php declare(strict_types=1);
namespace Mlab\Webhook\Services;

use GuzzleHttp\Client;
use Mlab\Webhook\Entities\RequestEntityInterface;
use Mlab\Webhook\Services\Interfaces\ClientResponse;
use Mlab\Webhook\Services\Interfaces\ClientException;
use Mlab\Webhook\Services\Interfaces\Client as ClientInterface;
use Mlab\Webhook\Entities\Http\HttpResponse;

class ClientService implements ClientInterface {

    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Send a GET request.
     *
     * @param string $url The request URL
     * @param array $headers Request headers
     * @return array Response data
     */
    public function get(string $url, array $headers = []): ClientResponse
    {
        $response = $this->request('GET', $url, [
            'headers' => $headers
        ]);
        
        return $response;
    }

    /**
     * Send a POST request.
     *
     * @param string $url The request URL
     * @param array $data Request body data
     * @param array $headers Request headers
     * @return array Response data
     */
    public function post(string $url, array $data, array $headers = []): ClientResponse
    {
        $response = $this->request('POST', $url, [
            'json' => $data,
            'headers' => $headers
        ]);
        
        return $response;
    }

    /**
     * Send a PUT request.
     *
     * @param string $url The request URL
     * @param array $data Request body data
     * @param array $headers Request headers
     * @return array Response data
     */
    public function put(string $url, array $data, array $headers = []): ClientResponse
    {
        $response = $this->request('PUT', $url, [
            'json' => $data,
            'headers' => $headers
        ]);
        
        return $response;
    }

    /**
     * Send a DELETE request.
     *
     * @param string $url The request URL
     * @param array $headers Request headers
     * @return array Response data
     */
    public function delete(string $url, array $headers = []): ClientResponse
    {
        $response = $this->request('DELETE', $url, [
            'headers' => $headers
        ]);
        
        return $response;
    }

    /**
     * Send a PATCH request.
     *
     * @param string $url The request URL
     * @param array $data Request body data
     * @param array $headers Request headers
     * @return array Response data
     */
    public function patch(string $url, array $data, array $headers = []): ClientResponse
    {
        $response = $this->request('PATCH', $url, [
            'json' => $data,
            'headers' => $headers
        ]);
        
        return $response;
    }

    /**
     * Send a custom request.
     *
     * @param string $method The request method
     * @param string $url The request URL
     * @param array $options Request options
     * @return ClientResponse Response data
     */
    public function request(string $method, string $url, array $options = []): ClientResponse
    {
        try {
            $response = $this->client->request($method, $url, $options);
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), $url, $method, $options);
        }

        return new HttpResponse($response);
    }

    /**
     * Get the payload for the connection.
     *
     * @return array The connection payload data.
     */
    public function payload(): array
    {
        return json_decode($this->client->getBody(), true);
    }

}