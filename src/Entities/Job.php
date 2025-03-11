<?php declare(strict_types=1);
namespace Mlab\Webhook\Entities;

final class Job {

    private $uuid;
    private $jobId;
    private Payload $payload;

    public function __construct(array $job) {

        if (!isset($job['uuid']) || !isset($job['data'])) {
            throw new \InvalidArgumentException('Job must have uuid and data');
        }

        $this->uuid = $job['uuid'];
        $this->jobId = $job['job'] ?? $job['displayName'] ?? '';
        $this->payload = new Payload($job['data']);
    }

    public function uuid(): string {
        return $this->uuid;
    }

    public function getJobId(): string {
        return $this->jobId;
    }

    public function payload(): Payload {
        return $this->payload;
    }

}