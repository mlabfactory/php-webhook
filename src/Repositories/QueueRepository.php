<?php
namespace Mlab\Webhook\Repositories;

use Illuminate\Database\ConnectionInterface;
use Mlab\Webhook\Services\DbServiceConnection;

class QueueRepository {
    
    private ConnectionInterface $connection;

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
        $this->connection->table('failed_jobs')->insert($data);
    }
}