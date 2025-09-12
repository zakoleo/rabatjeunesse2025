<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateFootballCategoriesTypes extends BaseMigration
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
        $table = $this->table('football_categories_types');
        
        // Add foreign key columns
        $table->addColumn('football_category_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('football_type_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        
        // Add timestamps
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        
        // Add indexes
        $table->addIndex(['football_category_id'], [
            'name' => 'BY_FOOTBALL_CATEGORY_ID',
        ]);
        $table->addIndex(['football_type_id'], [
            'name' => 'BY_FOOTBALL_TYPE_ID',
        ]);
        
        // Add unique constraint to prevent duplicates
        $table->addIndex(['football_category_id', 'football_type_id'], [
            'name' => 'UNIQUE_CATEGORY_TYPE',
            'unique' => true,
        ]);
        
        $table->create();
    }
}
