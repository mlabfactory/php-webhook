<?php
namespace Mlab\Webhook\Traits;

use Mlab\Webhook\Entities\Job;
use Mlab\Webhook\Helpers\Logger;
use Mlab\Webhook\Entities\Webhook;
use Mlab\Webhook\Entities\RequestEntityInterface;
use Mlab\Webhook\Services\Interfaces\ClientResponse;

trait WebhookHandler {

    abstract protected function message(): string;
    
    /**
     * Dispatch a job to the queue.
     *
     * @param  mixed  $job
     * @return mixed
     */
    /**
     * Dispatches a job to a webhook endpoint.
     *
     * @param array $job The job data to be dispatched
     * @return \Mlab\Webhook\Services\Interfaces\ClientResponse The response from the webhook endpoint
     * @throws \Exception If there is an error dispatching the webhook
     */
    public function dispatch(array $job): ClientResponse
    {   

        $message = $this->buildJob($job);

        $messageObj = $this->message();
        if(!is_subclass_of($messageObj, RequestEntityInterface::class)) {
            throw new \InvalidArgumentException('Message must implement RequestEntityInterface');
        }

        return $message->handle();
    }

    /**
     * Builds a webhook processing job from the provided job data.
     *
     * @param array $job The job data to build a processing job from
     * @return \Mlab\Webhook\Services\Queue\ProcessWebhookJob The built webhook processing job
     * @todo Should return an interface instead of concrete implementation
     */
    public function buildJob(array $job): \Mlab\Webhook\Services\Queue\ProcessWebhookJob //FIXME: hould be an interface
    {
        /** @var \Mlab\Webhook\Services\Queue\ProcessWebhookJob $command */
        $command = $this->unserialize($job['data']['command']);
        return $command;
    }

    /**
     * Unserializes the webhook data.
     * 
     * This method is part of the Serializable interface implementation 
     * that allows webhook objects to be serialized and unserialized.
     *
     * @param string $data The serialized data to restore the object from
     * @return void
     */
    public function unserialize($data)
    {
        return unserialize($data);
    }
}