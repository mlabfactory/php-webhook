<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FailedJobsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('failed_jobs', ['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id', 'biginteger', ['signed' => false, 'identity' => true])
            ->addColumn('uuid', 'string', ['limit' => 255, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('connection', 'text', ['collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('queue', 'text', ['collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('payload', 'text', ['collation' => 'utf8mb4_unicode_ci', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addColumn('exception', 'text', ['collation' => 'utf8mb4_unicode_ci', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG])
            ->addColumn('failed_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['uuid'], ['unique' => true, 'name' => 'failed_jobs_uuid_unique'])
            ->create();
    }
}
