<?php declare(strict_types=1);
namespace Mlab\Webhook\Repositories;

use Mdf\JsonStorage\Service\DbService;
use Mlab\Webhook\Models\QueueMapperModel;

class WebHookRepository {

    private DbService $dbService;

    public function __construct(DbService $dbService) {
        $this->dbService = $dbService;
    }

    public function findWebHookFromUuid(string $uuid): array {
        $db = $this->dbService;
        $queue = $db->createQuery()
            ->get($uuid);

        if(empty($queue)) {
            return [];
        }

        return $queue;
    }

    public function insert(QueueMapperModel $webhook): void {
        $this->dbService->insert($webhook);
    }
}