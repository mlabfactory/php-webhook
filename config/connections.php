<?php

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Query\Grammars\MySqlGrammar;

class Connection implements ConnectionResolverInterface {

    protected array $connections;
    private array $configurations;

    public function connection($name = null)
    {   
        $connectionData = $this->getDefaultConnection();

        $dsn = $this->getDsn($connectionData);
        $pdo = new \PDO($dsn, $connectionData['user'], $connectionData['pass']);
        
        $connection = new DatabaseConnection(
            $pdo, $connectionData['name'], $connectionData['prefix'] ?? ''
        );
        $connection->setQueryGrammar(new MySqlGrammar($connection));
        $connection->enableQueryLog();
        
        return $connection;
    }

    protected function getDsn($connectionData)
    {
        switch ($connectionData['adapter']) {
            case 'sqlite':
                return 'sqlite:' . $connectionData['name'];
            case 'mysql':
            case 'pgsql':
                return $connectionData['adapter'] . ':host=' . $connectionData['host'] . ';dbname=' . $connectionData['name'] . ';port=' . $connectionData['port'];
            case 'mongodb':
                // MongoDB DSN format can be different, adjust as needed
                return 'mongodb://' . $connectionData['host'] . ':' . $connectionData['port'];
            case 'redis':
                // Redis DSN format can be different, adjust as needed
                return 'redis://' . $connectionData['host'] . ':' . $connectionData['port'];
            default:
                throw new \InvalidArgumentException('Unsupported database adapter: ' . $connectionData['adapter']);
        }
    }

    public function getDefaultConnection(): array
    {
        return $this->connections;
    }

    public function setDefaultConnection($name)
    {
        $connections = [
            'mysql' => [
                'adapter' => 'mysql',
                'driver' => 'mysql',
                'host' => env('DB_HOST', 'localhost'),
                'name' => env('DB_DATABASE', 'database_name'),
                'user' => env('DB_USERNAME', 'username'),
                'pass' => env('DB_PASSWORD', 'password'),
                'port' => env('DB_PORT', '3306'),
                'charset' => 'utf8',
            ],
            'pgsql' => [
                'adapter' => 'pgsql',
                'driver' => 'pgsql',
                'host' => env('DB_HOST', 'localhost'),
                'name' => env('DB_DATABASE', 'database_name'),
                'user' => env('DB_USERNAME', 'username'),
                'pass' => env('DB_PASSWORD', 'password'),
                'port' => env('DB_PORT', '3306'),
                'charset' => 'utf8',
            ],
            'sqlite' => [
                'adapter' => 'sqlite',
                'name' => storage_path(env('DB_DATABASE', 'database_name')),
            ],
            'mongodb' => [
                'adapter' => 'mongodb',
                'driver' => 'mongodb',
                'host' => env('DB_HOST', 'localhost'),
                'name' => env('DB_DATABASE', 'database_name'),
                'user' => env('DB_USERNAME', 'username'),
                'pass' => env('DB_PASSWORD', 'password'),
                'port' => env('DB_PORT', '3306'),
            ],
            'redis' => [
                'adapter' => 'redis',
                'driver' => 'redis',
                'host' => env('DB_HOST', 'localhost'),
                'user' => env('DB_USERNAME', ''),
                'pass' => env('DB_PASSWORD', ''),
                'port' => env('DB_PORT', '6379'),
            ],
        ];

        if (!isset($connections[$name])) {
            throw new \InvalidArgumentException('Unsupported connection name: ' . $name);
        }

        $this->configurations = $connections;
        $this->connections = $connections[$name];
    }

    /**
     * Get the value of configurations
     *
     * @return array
     */
    public function getConfigurations(): array
    {
        $this->configurations['default'] = $this->connections;
        return $this->configurations;
    }
}

$_connection = new Connection();
$_connection->setDefaultConnection(env('DB_CONNECTION'));