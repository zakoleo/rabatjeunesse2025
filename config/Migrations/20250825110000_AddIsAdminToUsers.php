<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddIsAdminToUsers extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        $table = $this->table('users');
        $table->addColumn('is_admin', 'boolean', [
            'default' => false,
            'null' => false,
            'after' => 'email'
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
        $table = $this->table('users');
        $table->removeColumn('is_admin');
        $table->update();
    }
}