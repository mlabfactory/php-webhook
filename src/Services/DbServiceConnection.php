<?php

namespace Mlab\Webhook\Services;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\SQLiteConnection;

class DbServiceConnection {

    private ConnectionInterface $connection;

    public function __construct(array $config) {
        $this->setConnection($config);
    }

    protected function setConnection(array $config): void {

        $pdo = new \PDO(
            $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['name'],
            $config['user'],
            $config['pass']
        );

        switch ($config['driver']) {
            case 'mysql':
                $this->connection = new MySqlConnection($pdo);
                break;
            case 'pgsql':
                $this->connection = new PostgresConnection($pdo);
                break;
            case 'sqlite':
                $this->connection = new SQLiteConnection($pdo);
                break;
            default:
                throw new \InvalidArgumentException('Unsupported database driver');
        }
    }

    /**
     * Get the value of connection
     */ 
    public function connection()
    {
        return $this->connection;
    }
}