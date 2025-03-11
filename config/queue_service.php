<?php

use Mdf\JsonStorage\Service\DbService;

$queue_service = new DbService(storage_path());
$queue_service->setTableName('queue');