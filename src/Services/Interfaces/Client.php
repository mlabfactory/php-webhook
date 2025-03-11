<?php declare(strict_types=1);
namespace Mlab\Webhook\Services\Interfaces;

interface Client {
    
    public function get(string $url, array $headers = []): ClientResponse;

    public function post(string $url, array $data, array $headers = []): ClientResponse;

    public function put(string $url, array $data, array $headers = []): ClientResponse;

    public function delete(string $url, array $headers = []): ClientResponse;

    public function patch(string $url, array $data, array $headers = []): ClientResponse;

    public function request(string $method, string $url, array $options = []): ClientResponse;

    public function payload(): array;
    
}