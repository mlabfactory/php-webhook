<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class JobsTable extends AbstractMigration
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
        $table = $this->table('jobs');
        $table->addColumn('queue', 'string', ['limit' => 255, 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('payload', 'text', ['collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('attempts', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'signed' => false])
            ->addColumn('reserved_at', 'integer', ['signed' => false, 'null' => true, 'default' => null])
            ->addColumn('available_at', 'integer', ['signed' => false])
            ->addColumn('created_at', 'integer', ['signed' => false])
            ->addIndex(['queue'], ['name' => 'jobs_queue_index', 'unique' => false])
            ->create();
    }
}
