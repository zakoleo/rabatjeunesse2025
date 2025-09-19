<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixVerificationNotesNullable extends BaseMigration
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
        // Fix verification_notes column to allow NULL values
        $table = $this->table('teams');
        $table->changeColumn('verification_notes', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}