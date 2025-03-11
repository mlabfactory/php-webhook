<?php declare(strict_types=1);
namespace Mlab\Webhook\Services\Interfaces;

interface ClientResponse {
    
    public function getStatusCode(): int;

    public function getBody(): string;

    public function getHeaders(): array;

    public function getHeader(string $name): string;

    public function getError(): ?string;
    
}