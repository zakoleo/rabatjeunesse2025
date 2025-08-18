<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class DropResponsablesEntraineursTable extends BaseMigration
{
    public function up(): void
    {
        $this->table('responsables')->drop()->save();
        $this->table('entraineurs')->drop()->save();
    }
    
    public function down(): void
    {
        // Recréer les tables si besoin de rollback
        $responsables = $this->table('responsables');
        $responsables->addColumn('nom_complet', 'string', ['limit' => 255])
                     ->addColumn('date_naissance', 'date')
                     ->addColumn('tel', 'string', ['limit' => 20])
                     ->addColumn('whatsapp', 'string', ['limit' => 20, 'null' => true])
                     ->addColumn('cin_recto', 'string', ['limit' => 255, 'null' => true])
                     ->addColumn('cin_verso', 'string', ['limit' => 255, 'null' => true])
                     ->addColumn('team_id', 'integer')
                     ->addColumn('created', 'datetime')
                     ->addColumn('modified', 'datetime')
                     ->create();
                     
        $entraineurs = $this->table('entraineurs');
        $entraineurs->addColumn('nom_complet', 'string', ['limit' => 255])
                    ->addColumn('date_naissance', 'date')
                    ->addColumn('tel', 'string', ['limit' => 20])
                    ->addColumn('whatsapp', 'string', ['limit' => 20, 'null' => true])
                    ->addColumn('cin_recto', 'string', ['limit' => 255, 'null' => true])
                    ->addColumn('cin_verso', 'string', ['limit' => 255, 'null' => true])
                    ->addColumn('team_id', 'integer')
                    ->addColumn('same_as_responsable', 'boolean', ['null' => true])
                    ->addColumn('created', 'datetime')
                    ->addColumn('modified', 'datetime')
                    ->create();
    }
}