<?php

use GuzzleHttp\Psr7\Response;

if(!function_exists('storage_path')) {
    function storage_path($path = '') {
        return __DIR__ . '/../storage/' . $path;
    }
}

if(!function_exists('config_path')) {
    function config_path($path = '') {
        return __DIR__ . '/../config/' . $path;
    }
}

if(!function_exists('public_path')) {
    function public_path($path = '') {
        return __DIR__ . '/../public/' . $path;
    }
}

if(!function_exists('env')) {
    function env(string $key, $value) {
        return $_ENV[$key] ?? $value;
    }
}

if(!function_exists('response')) {
    function response(int $status = 200, string $body = '') {
        header('Content-Type: application/json');
        http_response_code($status);
        echo $body;
    }
}

if(!function_exists('generate_hash')) {
    function generate_hash(array $data): string
    {
        $arrayToString = implode($data);
        return hash('sha256', $arrayToString);
    }
}