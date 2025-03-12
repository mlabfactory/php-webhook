<?php
namespace Mlab\Webhook\Models;

use Mdf\JsonStorage\Domain\Model\JsonModel;
use Mdf\JsonStorage\Domain\Model\JsonModelInterface;

class QueueMapperModel extends JsonModel implements JsonModelInterface {

    private string $domain;

    public static function create(array $data): JsonModelInterface
    {
        $model = new self();
        $model->setDomain($data['domain']);
        
        return $model;
    }


    /**
     * Set the value of domain
     *
     * @param string $domain
     *
     * @return self
     */
    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get the value of domain
     *
     * @return string
     */
    public function getdomain(): string
    {
        return $this->domain;
    }
}