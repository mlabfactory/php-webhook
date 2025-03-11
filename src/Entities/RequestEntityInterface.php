<?php declare(strict_types=1);
namespace Mlab\Webhook\Entities;

use Mlab\Webhook\Services\Interfaces\Client;
use Mlab\Webhook\Services\Interfaces\ClientResponse;

/**
 * Interface for classes that represent an external API request entity.
 * 
 * Classes implementing this interface are responsible for encapsulating
 * the data and behavior of request entities, providing a standardized way
 * to interact with external API requests within the application.
 * 
 * @package MLAB\Webhook\Entities
 */
interface RequestEntityInterface {

    /**
     * Get the request path.
     *
     * @return string The path component of the request URI
     */
    public function path(): string;

    /**
     * Gets the request payload.
     *
     * @return array The request payload as an associative array.
     */
    public function payload(): array;

    /**
     * Get the headers of the request.
     *
     * @return array An array of request headers.
     */
    public function headers(): array;
    
    /**
     * Returns the HTTP method used in the request.
     * 
     * This method retrieves the HTTP verb (GET, POST, PUT, DELETE, etc.) 
     * associated with the current request entity.
     * 
     * @return string The HTTP method as a string
     */
    public function method(): string;

    /**
     * Sets the client service for the request entity.
     *
     * @param string $clientName The name of the client service
     * @return void
     */
    public function setClient(string|Client $clientName): void;

    /**
     * Invokes the request entity.
     *
     * This method is called when the entity is being processed.
     * It should contain the main execution logic for handling the request.
     *
     * @return ClientResponse The response from the request
     */
    public function invoke(): ClientResponse;

}