<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddVerificationNotesToTeams extends BaseMigration
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
        $table = $this->table('teams');
        $table->addColumn('verification_notes', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
