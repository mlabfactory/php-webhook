<?php

namespace Mlab\Webhook\Services;

use Carbon\Carbon;
use Exception;
use Mlab\Webhook\Helpers\Logger;
use Mlab\Webhook\Entities\Webhook;
use Illuminate\Queue\Jobs\DatabaseJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mlab\Webhook\Services\Interfaces\Client;
use Illuminate\Queue\Capsule\Manager as Queue;
use Mlab\Webhook\Models\FailedJob;
use Mlab\Webhook\Services\Queue\ProcessWebhookJob;
use Mlab\Webhook\Traits\WebhookHandler;

class QueueService implements ShouldQueue
{
    use WebhookHandler;

    private $queue;
    private $batchSize = 10;
    private $processInterval = 60; // secondi
    protected $queueName = 'default';

    protected Client $client;

    public function __construct(Queue $queue, Client $client)
    {
        $this->queue = $queue;
        $this->client = $client;
    }

    protected function message(): string
    {
        return Webhook::class;
    }

    /**
     * Accoda una richiesta webhook
     */
    public function enqueue(Webhook $webhook, ?string $queue = null): bool
    {
        $job = new ProcessWebhookJob($webhook);

        try {
            $this->queue->push($job, '', $queue);

            Logger::info('Webhook accodato con successo', [
                'url' => $webhook->path(),
                'method' => $webhook->method()
            ]);

            return true;
        } catch (\Exception $e) {
            Logger::error('Errore nell\'accodamento del webhook', [
                'error' => $e->getMessage(),
                'url' => $webhook->path()
            ]);

            return false;
        }
    }

    /**
     * Elabora i webhook in coda
     */
    public function processQueue(): void
    {
        Logger::info('Inizio elaborazione della coda webhook');

        try {
            
            $queue = $this->queue->connection();
            $queueSize = $queue->size();

            if ($queueSize === 0) {
                Logger::info('Nessun webhook da elaborare');
                return;
            }

            // Process up to batchSize jobs
            $processed = 0;
            while ($processed < $this->batchSize) {
                $job = $queue->pop();
                if ($job instanceof DatabaseJob) {
                    $this->processJob($job);
                    $processed++;
                } else {
                    Logger::warning('Job non valido ricevuto dalla coda');
                    break;
                }
            }

            Logger::info('Elaborazione completata', [
                'processed' => $processed,
                'queue_size' => $queueSize
            ]);
            

        } catch (\Exception $e) {
            Logger::critical('Errore durante l
            \'elaborazione della coda', [
                'error' => $e->getMessage()
            ]);
        }
    }


    /**
     * Processes a job retrieved from the database.
     * 
     * This method handles the execution of a job, including any necessary
     * processing logic specific to the job type.
     * 
     * @param DatabaseJob $job The job to be processed
     * @return void
     * 
     * @throws \Exception If there is an error during job processing
     */
    private function processJob(DatabaseJob $job): void
    {
        try {

            $response = $this->dispatch(
                $job->payload()
            );

            if($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                throw new \Mlab\Webhook\Services\Queue\QueueProcessingException('Errore durante l\'invio del webhook', $response);
            }

            $job->delete();
            Logger::info('Webhook inviato con successo', $job->payload());


        } catch (\Exception $e) {
            $this->fail($job, $e);

            Logger::error('Errore nell\'elaborazione del job', [
                'error' => $e->getMessage(),
                'job_id' => $job->getJobId(),
                'payload' => $job->payload()
            ]);

        }
    }

    /**
     * Marks the job as failed in the database.
     *
     * This method is responsible for updating the status of a job in the database
     * to indicate that it has failed during processing.
     *
     * @param DatabaseJob $job The job that has failed
     * @return void
     * @throws \Exception Potentially thrown by database operations
     */
    protected function fail(DatabaseJob $job, Exception $e) {

        if ($job->attempts() >= 3) {
            $job->markAsFailed();
            
            FailedJob::create([
                'connection' => $this->queue->getQueueManager()->getName(),
                'queue' => $job->getQueue(),
                'payload' => $job->getRawBody(),
                'exception' => $e->getMessage(),
                'failed_at' => Carbon::now()->toAtomString()
            ]);

            Logger::info('Job saved on failed table', [
                'job_id' => $job->getJobId(),
                'payload' => $job->payload()
            ]);
        } else {
            $this->retry($job, $job->attempts(), 3);
        }
    }

    /**
     * Handles retry logic for webhook jobs.
     *
     * @param mixed $job The job object to be retried
     * @param int $attempt The current attempt number
     * @param int $maxAttempts The maximum number of retry attempts allowed
     * @return void
     */
    protected function retry($job, int $attempt, int $maxAttempts): void
    {
        if ($job->attempts() >= $maxAttempts) { // Imposta il limite massimo di retry
            $job->fail();

            Logger::error('Job fallito definitivamente', [
                'job_id' => $job->getJobId(),
                'payload' => $job->payload()
            ]);
            
        } else {
            $job->release($attempt);
        }
    }

}