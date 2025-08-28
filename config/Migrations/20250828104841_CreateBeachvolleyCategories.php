<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateBeachvolleyCategories extends BaseMigration
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
        $table = $this->table('beachvolley_categories', [
            'collation' => 'utf8mb3_general_ci'
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('age_range', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('min_date', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('max_date', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('min_birth_year', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('max_birth_year', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('active', 'boolean', [
            'default' => true,
            'null' => false,
        ]);
        $table->addTimestamps();
        $table->create();
    }
}
