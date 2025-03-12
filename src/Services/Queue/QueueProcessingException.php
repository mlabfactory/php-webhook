<?php
namespace Mlab\Webhook\Services\Queue;

use Mlab\Webhook\Services\Interfaces\ClientResponse;

class QueueProcessingException extends \Exception {

    private ClientResponse $response;

    public function __construct(
        string $message,
        ClientResponse $response,
        int $code = 0,
    ) {
        $this->response = $response;
        $message = $message . ' - ' . $this->getReason();

        parent::__construct($message, $code);
    }

    /**
     * Get the reason for the exception.
     *
     * @return string The reason why the queue processing failed
     */
    public function  getReason(): string
    {
        $reason = $this->response->getBody();
        if (empty($reason)) {
            $reason = $this->response->getError();
        }

        return $reason;
    }

}