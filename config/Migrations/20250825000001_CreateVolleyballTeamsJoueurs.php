<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateVolleyballTeamsJoueurs extends BaseMigration
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
        $table = $this->table('volleyball_teams_joueurs');
        $table->addColumn('nom_complet', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('date_naissance', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('identifiant', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('taille_vestimentaire', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => false,
        ]);
        $table->addColumn('volleyball_team_id', 'integer', [
            'default' => null,
            'limit' => 11,
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
        $table->addForeignKey('volleyball_team_id', 'volleyball_teams', 'id', [
            'delete' => 'CASCADE',
            'update' => 'NO_ACTION'
        ]);
        $table->create();
    }
}