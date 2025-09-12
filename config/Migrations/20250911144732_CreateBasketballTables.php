<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateBasketballTables extends BaseMigration
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
        // Create basketball_types table
        $basketballTypes = $this->table('basketball_types');
        $basketballTypes
            ->addColumn('name', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('code', 'string', ['limit' => 10, 'null' => false])
            ->addColumn('min_players', 'integer', ['null' => false])
            ->addColumn('max_players', 'integer', ['null' => false])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        // Create basketball_categories_types junction table
        $basketballCategoriesTypes = $this->table('basketball_categories_types');
        $basketballCategoriesTypes
            ->addColumn('basketball_category_id', 'integer', ['null' => false])
            ->addColumn('basketball_type_id', 'integer', ['null' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['basketball_category_id', 'basketball_type_id'], ['unique' => true])
            ->addForeignKey('basketball_category_id', 'basketball_categories', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->addForeignKey('basketball_type_id', 'basketball_types', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->create();
    }
}
