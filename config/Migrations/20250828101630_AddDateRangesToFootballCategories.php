<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddDateRangesToFootballCategories extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        $table = $this->table('football_categories');
        $table->addColumn('min_birth_year', 'integer', [
            'after' => 'age_range',
            'null' => true,
            'comment' => 'Minimum birth year for this category'
        ]);
        $table->addColumn('max_birth_year', 'integer', [
            'after' => 'min_birth_year',
            'null' => true,
            'comment' => 'Maximum birth year for this category'
        ]);
        $table->addColumn('min_date', 'string', [
            'after' => 'max_birth_year',
            'limit' => 10,
            'null' => true,
            'comment' => 'Minimum birth date (YYYY-MM-DD format)'
        ]);
        $table->addColumn('max_date', 'string', [
            'after' => 'min_date',
            'limit' => 10,
            'null' => true,
            'comment' => 'Maximum birth date (YYYY-MM-DD format)'
        ]);
        $table->update();
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        $table = $this->table('football_categories');
        $table->removeColumn('max_date');
        $table->removeColumn('min_date');
        $table->removeColumn('max_birth_year');
        $table->removeColumn('min_birth_year');
        $table->update();
    }
}
