<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateHandballTablesStructure extends BaseMigration
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
        // Drop existing handball tables if they exist to recreate with proper structure
        if ($this->hasTable('handball_teams_joueurs')) {
            $this->table('handball_teams_joueurs')->drop()->save();
        }
        
        if ($this->hasTable('handball_teams')) {
            $this->table('handball_teams')->drop()->save();
        }

        // Create handball_teams table with proper structure matching basketball
        $handballTeams = $this->table('handball_teams');
        $handballTeams
            ->addColumn('nom_equipe', 'string', [
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('categorie', 'string', [
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('genre', 'string', [
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('type_handball', 'string', [
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('district', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('organisation', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('adresse', 'text', [
                'null' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('reference_inscription', 'string', [
                'limit' => 50,
                'null' => true,
            ])
            // Foreign key columns (handball-specific names but reference football tables)
            ->addColumn('handball_category_id', 'integer', [
                'null' => true,
            ])
            ->addColumn('handball_district_id', 'integer', [
                'null' => true,
            ])
            ->addColumn('handball_organisation_id', 'integer', [
                'null' => true,
            ])
            // Responsable fields
            ->addColumn('responsable_nom_complet', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('responsable_date_naissance', 'date', [
                'null' => true,
            ])
            ->addColumn('responsable_tel', 'string', [
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('responsable_whatsapp', 'string', [
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('responsable_cin_recto', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('responsable_cin_verso', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            // Entraineur fields
            ->addColumn('entraineur_nom_complet', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('entraineur_date_naissance', 'date', [
                'null' => true,
            ])
            ->addColumn('entraineur_tel', 'string', [
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('entraineur_whatsapp', 'string', [
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('entraineur_cin_recto', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('entraineur_cin_verso', 'string', [
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('entraineur_same_as_responsable', 'boolean', [
                'default' => false,
                'null' => true,
            ])
            // Agreement and timestamps
            ->addColumn('accepter_reglement', 'boolean', [
                'default' => false,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            // Indexes
            ->addIndex(['user_id'])
            ->addIndex(['handball_category_id'])
            ->addIndex(['handball_district_id'])
            ->addIndex(['handball_organisation_id'])
            ->addIndex(['reference_inscription'], ['unique' => true])
            ->create();

        // Create handball_teams_joueurs table
        $handballJoueurs = $this->table('handball_teams_joueurs');
        $handballJoueurs
            ->addColumn('nom_complet', 'string', [
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('date_naissance', 'date', [
                'null' => false,
            ])
            ->addColumn('identifiant', 'string', [
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('taille_vestimentaire', 'string', [
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('handball_team_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addIndex(['handball_team_id'])
            ->create();
    }
}