<?php declare(strict_types=1);
namespace Mlab\Webhook\Repositories;

use Illuminate\Database\ConnectionInterface;
use Mlab\Webhook\Services\DbServiceConnection;

class WebHookRepository implements RepositoryInterface {

    private ConnectionInterface $connection;
    const TABLE = 'webhooks';

    public function __construct(DbServiceConnection $dbServiceConnection) {
        $this->connection = $dbServiceConnection->connection();
    }

    /**
     * Insert a failed job into the queue storage.
     *
     * This method stores information about a job that failed during execution.
     *
     * @param array $data Data representing the failed job to be stored
     * @return void
     */
    public function create(array $data): void {
        $this->connection->table(self::TABLE)->insert($data);
    }

    public function findWebHookFromUuid(string $uuid): array {
        $this->connection->table(self::TABLE)->where('uuid', $uuid)->first();

        if(empty($queue)) {
            return [];
        }

        return $queue;
    }

    public function update(string $uuid, array $data): void {
        $this->connection->table(self::TABLE)->where('uuid', $uuid)->update($data);
    }

    public function delete(string $uuid): void {
        $this->connection->table(self::TABLE)->where('uuid', $uuid)->delete();
    }

    public function find(string $uuid): array {
        $this->connection->table(self::TABLE)->where('uuid', $uuid)->first();

        if(empty($queue)) {
            return [];
        }

        return $queue;
    }

}