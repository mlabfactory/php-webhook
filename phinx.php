<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/load_env.php';
require_once __DIR__ . '/config/connections.php';

return [
    'paths' => [
        'migrations' =>  'resources/database/migrations',
        'seeds' => 'resources/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => $_connection->getDefaultConnection(),
    ],
    'version_order' => 'creation'
];
