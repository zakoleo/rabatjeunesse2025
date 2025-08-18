<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateTeamsForFootballReferences extends BaseMigration
{
    public function up(): void
    {
        $table = $this->table('teams');
        
        // Ajouter les nouvelles colonnes pour les clés étrangères
        $table->addColumn('football_category_id', 'integer', [
            'after' => 'categorie',
            'null' => true
        ]);
        $table->addColumn('football_district_id', 'integer', [
            'after' => 'district',
            'null' => true
        ]);
        $table->addColumn('football_organisation_id', 'integer', [
            'after' => 'organisation',
            'null' => true
        ]);
        
        // Ajouter les clés étrangères
        $table->addForeignKey('football_category_id', 'football_categories', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ]);
        $table->addForeignKey('football_district_id', 'football_districts', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ]);
        $table->addForeignKey('football_organisation_id', 'football_organisations', 'id', [
            'delete' => 'RESTRICT',
            'update' => 'CASCADE'
        ]);
        
        $table->update();
    }
    
    public function down(): void
    {
        $table = $this->table('teams');
        
        // Supprimer les clés étrangères
        $table->dropForeignKey('football_category_id');
        $table->dropForeignKey('football_district_id');
        $table->dropForeignKey('football_organisation_id');
        
        // Supprimer les colonnes
        $table->removeColumn('football_category_id');
        $table->removeColumn('football_district_id');
        $table->removeColumn('football_organisation_id');
        
        $table->update();
    }
}