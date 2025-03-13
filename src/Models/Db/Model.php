<?php
namespace Mlab\Webhook\Models\Db;

use Mlab\Webhook\Repositories\RepositoryInterface;

class Model {
    
    protected RepositoryInterface $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Insert a failed job into the queue storage.
     *
     * This method stores information about a job that failed during execution.
     *
     * @param array $data Data representing the failed job to be stored
     * @return void
     */
    public function insertFailedJob(array $data): void {
        $this->repository->create($data);
    }

    public static function __callStatic($name, $arguments)
    {
        global $container;
        $calledClass = get_called_class();
        $repo = $container->get($calledClass);
        $model = new static($repo->repository);
        return $model->repository->$name(...$arguments);
    }
}