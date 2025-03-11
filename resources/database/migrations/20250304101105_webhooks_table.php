<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class WebhooksTable extends AbstractMigration
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
        $table = $this->table('webhooks', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'uuid')
            ->addColumn('name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('destination_url', 'string', ['limit' => 2048, 'null' => false])
            ->addColumn('method', 'string', ['limit' => 10, 'default' => 'POST'])
            ->addColumn('headers', 'json', ['null' => true])
            ->addColumn('payload_template', 'text', ['null' => true])
            ->addColumn('secret', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('retry_count', 'integer', ['default' => 0, 'null' => false])
            ->addColumn('max_retries', 'integer', ['default' => 3, 'null' => false])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();

        $table = $this->table('webhook_requests', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'uuid')
            ->addColumn('webhook_id', 'uuid')
            ->addColumn('status', 'string', ['limit' => 20]) // pending, success, failed
            ->addColumn('request_headers', 'json', ['null' => true])
            ->addColumn('request_payload', 'text', ['null' => true])
            ->addColumn('response_code', 'integer', ['null' => true])
            ->addColumn('response_body', 'text', ['null' => true])
            ->addColumn('response_headers', 'json', ['null' => true])
            ->addColumn('error_message', 'text', ['null' => true])
            ->addColumn('attempts', 'integer', ['default' => 0])
            ->addColumn('executed_at', 'timestamp', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('webhook_id', 'webhooks', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
