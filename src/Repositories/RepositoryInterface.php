<?php
namespace Mlab\Webhook\Repositories;

interface RepositoryInterface
{
    public function create(array $data): void;
    public function find(string $uuid): array;
    public function update(string $uuid, array $data): void;
    public function delete(string $uuid): void;
}