<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAllSportsTables extends BaseMigration
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
        // Create volleyball_types table
        $volleyballTypes = $this->table('volleyball_types');
        $volleyballTypes
            ->addColumn('name', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('code', 'string', ['limit' => 10, 'null' => false])
            ->addColumn('min_players', 'integer', ['null' => false])
            ->addColumn('max_players', 'integer', ['null' => false])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        // Create volleyball_categories_types junction table
        $volleyballCategoriesTypes = $this->table('volleyball_categories_types');
        $volleyballCategoriesTypes
            ->addColumn('volleyball_category_id', 'integer', ['null' => false])
            ->addColumn('volleyball_type_id', 'integer', ['null' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['volleyball_category_id', 'volleyball_type_id'], ['unique' => true])
            ->addForeignKey('volleyball_category_id', 'volleyball_categories', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->addForeignKey('volleyball_type_id', 'volleyball_types', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->create();

        // Create beachvolley_types table
        $beachvolleyTypes = $this->table('beachvolley_types');
        $beachvolleyTypes
            ->addColumn('name', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('code', 'string', ['limit' => 10, 'null' => false])
            ->addColumn('min_players', 'integer', ['null' => false])
            ->addColumn('max_players', 'integer', ['null' => false])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        // Create beachvolley_categories_types junction table
        $beachvolleyCategoriesTypes = $this->table('beachvolley_categories_types');
        $beachvolleyCategoriesTypes
            ->addColumn('beachvolley_category_id', 'integer', ['null' => false])
            ->addColumn('beachvolley_type_id', 'integer', ['null' => false])
            ->addColumn('created', 'datetime', ['null' => true])
            ->addColumn('modified', 'datetime', ['null' => true])
            ->addIndex(['beachvolley_category_id', 'beachvolley_type_id'], ['unique' => true])
            ->addForeignKey('beachvolley_category_id', 'beachvolley_categories', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->addForeignKey('beachvolley_type_id', 'beachvolley_types', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->create();
    }
}
