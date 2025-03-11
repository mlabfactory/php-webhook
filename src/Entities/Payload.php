<?php declare(strict_types=1);
namespace Mlab\Webhook\Entities;

class Payload {

    private string $url;
    private string $method;
    private array $payload;
    private array $headers;
    private int $attempts;
    private int $max_attempts;
    private int $created_at;

    public function __construct(array $payload) {
        $this->url = $payload['url'] ?? '';
        $this->method = $payload['method'] ?? '';
        $this->payload = $payload['payload'] ?? [];
        $this->headers = $payload['headers'] ?? [];
        $this->attempts = $payload['attempts'] ?? 0;
        $this->max_attempts = $payload['max_attempts'] ?? 0;
        $this->created_at = $payload['created_at'] ?? time();
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getPayload(): array {
        return $this->payload;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function getAttempts(): int {
        return $this->attempts;
    }

    public function getMaxAttempts(): int {
        return $this->max_attempts;
    }

    public function getCreatedAt(): int {
        return $this->created_at;
    }

}