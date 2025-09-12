<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateHandballTables extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        // Create handball_types table
        $handballTypes = $this->table('handball_types');
        $handballTypes
            ->addColumn('name', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('code', 'string', ['limit' => 10, 'null' => false])
            ->addColumn('min_players', 'integer', ['null' => false])
            ->addColumn('max_players', 'integer', ['null' => false])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        // Create handball_categories_types junction table
        $handballCategoriesTypes = $this->table('handball_categories_types');
        $handballCategoriesTypes
            ->addColumn('handball_category_id', 'integer', ['null' => false])
            ->addColumn('handball_type_id', 'integer', ['null' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['handball_category_id', 'handball_type_id'], ['unique' => true])
            ->addForeignKey('handball_category_id', 'handball_categories', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->addForeignKey('handball_type_id', 'handball_types', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->create();
    }
}
