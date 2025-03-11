<?php
namespace Mlab\Webhook\Models;

use Mdf\JsonStorage\Domain\Model\JsonModel;
use Mdf\JsonStorage\Domain\Model\JsonModelInterface;

class QueueMapperModel extends JsonModel implements JsonModelInterface {

    private string $queueName;
    private string $followUpUri;

    public static function create(array $data): JsonModelInterface
    {
        $model = new self();
        $model->setQueueName($data['queueName']);
        $model->setFollowUpUri($data['followUpUri']);
        
        return $model;
    }

    /**
     * Set the value of queueName
     *
     * @param string $queueName
     *
     * @return self
     */
    public function setQueueName(string $queueName): self
    {
        $this->queueName = $queueName;

        return $this;
    }


    /**
     * Set the value of followUpUri
     *
     * @param string $followUpUri
     *
     * @return self
     */
    public function setFollowUpUri(string $followUpUri): self
    {
        $this->followUpUri = $followUpUri;

        return $this;
    }

    /**
     * Get the value of queueName
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * Get the value of followUpUri
     *
     * @return string
     */
    public function getFollowUpUri(): string
    {
        return $this->followUpUri;
    }
}