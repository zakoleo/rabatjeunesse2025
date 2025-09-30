<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateConcoursCategories extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('concours_categories');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('gender', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => false,
        ]);
        $table->addColumn('age_category', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => false,
        ]);
        $table->addColumn('date_range_start', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('date_range_end', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('min_participants', 'integer', [
            'default' => 1,
            'null' => false,
        ]);
        $table->addColumn('max_participants', 'integer', [
            'default' => 1,
            'null' => false,
        ]);
        $table->addColumn('active', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex(['gender', 'age_category'], ['unique' => true]);
        $table->create();
    }
}