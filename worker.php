<?php

require __DIR__ . '/bootstrap/app.php';

$queueService = $container->get(\Mlab\Webhook\Services\QueueService::class);

echo "Worker webhook avviato...\n";

while (true) {
    $queueService->processQueue();
    echo "Elaborazione completata\n";
    sleep(15); // Attendi 60 secondi tra le elaborazioni
}