<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixJoueursCascadeDelete extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Gérer la contrainte de clé étrangère
        $table = $this->table('joueurs');
        
        // Vérifier si une contrainte existe et la supprimer
        $adapter = $this->getAdapter();
        $exists = $adapter->hasForeignKey('joueurs', 'team_id');
        
        if ($exists) {
            $table->dropForeignKey('team_id');
            $table->save();
        }
        
        // Ajouter la nouvelle contrainte avec CASCADE DELETE
        $table->addForeignKey('team_id', 'teams', 'id', [
            'delete' => 'CASCADE',
            'update' => 'CASCADE'
        ]);
        $table->save();
    }
    
    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        // Revenir à l'ancienne contrainte sans CASCADE
        $table = $this->table('joueurs');
        
        $table->dropForeignKey('team_id');
        $table->save();
        
        $table->addForeignKey('team_id', 'teams', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ]);
        $table->save();
    }
}
