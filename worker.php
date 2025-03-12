<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ERROR | E_PARSE); // Only report fatal errors and parse errors, disable warnings

require __DIR__ . '/bootstrap/app.php';

$queueService = $container->get(\Mlab\Webhook\Services\QueueService::class);

echo date('Y-m-d H:i:s') . " - Worker webhook avviato...\n";

while (true) {
    $queueService->processQueue();
    echo date('Y-m-d H:i:s') . " - Elaborazione completata\n";
    sleep(1); // Attendi 60 secondi tra le elaborazioni
}