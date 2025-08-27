<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateVolleyballTablesStructure extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Add missing columns to volleyball_teams table
        $table = $this->table('volleyball_teams');
        
        if (!$table->hasColumn('type_volleyball')) {
            $table->addColumn('type_volleyball', 'string', [
                'default' => '6x6',
                'limit' => 10,
                'null' => false,
                'after' => 'genre'
            ]);
        }
        
        if (!$table->hasColumn('reference_inscription')) {
            $table->addColumn('reference_inscription', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
                'after' => 'user_id'
            ]);
        }
        
        if (!$table->hasColumn('volleyball_category_id')) {
            $table->addColumn('volleyball_category_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
                'after' => 'reference_inscription'
            ]);
        }
        
        if (!$table->hasColumn('volleyball_district_id')) {
            $table->addColumn('volleyball_district_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
                'after' => 'volleyball_category_id'
            ]);
        }
        
        if (!$table->hasColumn('volleyball_organisation_id')) {
            $table->addColumn('volleyball_organisation_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
                'after' => 'volleyball_district_id'
            ]);
        }
        
        // Add responsable fields
        if (!$table->hasColumn('responsable_nom_complet')) {
            $table->addColumn('responsable_nom_complet', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'volleyball_organisation_id'
            ]);
        }
        
        if (!$table->hasColumn('responsable_date_naissance')) {
            $table->addColumn('responsable_date_naissance', 'date', [
                'default' => null,
                'null' => true,
                'after' => 'responsable_nom_complet'
            ]);
        }
        
        if (!$table->hasColumn('responsable_tel')) {
            $table->addColumn('responsable_tel', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'after' => 'responsable_date_naissance'
            ]);
        }
        
        if (!$table->hasColumn('responsable_whatsapp')) {
            $table->addColumn('responsable_whatsapp', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'after' => 'responsable_tel'
            ]);
        }
        
        if (!$table->hasColumn('responsable_cin_recto')) {
            $table->addColumn('responsable_cin_recto', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'responsable_whatsapp'
            ]);
        }
        
        if (!$table->hasColumn('responsable_cin_verso')) {
            $table->addColumn('responsable_cin_verso', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'responsable_cin_recto'
            ]);
        }
        
        // Add entraineur fields
        if (!$table->hasColumn('entraineur_nom_complet')) {
            $table->addColumn('entraineur_nom_complet', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'responsable_cin_verso'
            ]);
        }
        
        if (!$table->hasColumn('entraineur_date_naissance')) {
            $table->addColumn('entraineur_date_naissance', 'date', [
                'default' => null,
                'null' => true,
                'after' => 'entraineur_nom_complet'
            ]);
        }
        
        if (!$table->hasColumn('entraineur_tel')) {
            $table->addColumn('entraineur_tel', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'after' => 'entraineur_date_naissance'
            ]);
        }
        
        if (!$table->hasColumn('entraineur_whatsapp')) {
            $table->addColumn('entraineur_whatsapp', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'after' => 'entraineur_tel'
            ]);
        }
        
        if (!$table->hasColumn('entraineur_cin_recto')) {
            $table->addColumn('entraineur_cin_recto', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'entraineur_whatsapp'
            ]);
        }
        
        if (!$table->hasColumn('entraineur_cin_verso')) {
            $table->addColumn('entraineur_cin_verso', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'entraineur_cin_recto'
            ]);
        }
        
        if (!$table->hasColumn('entraineur_same_as_responsable')) {
            $table->addColumn('entraineur_same_as_responsable', 'boolean', [
                'default' => false,
                'null' => true,
                'after' => 'entraineur_cin_verso'
            ]);
        }
        
        if (!$table->hasColumn('accepter_reglement')) {
            $table->addColumn('accepter_reglement', 'boolean', [
                'default' => false,
                'null' => true,
                'after' => 'entraineur_same_as_responsable'
            ]);
        }
        
        $table->update();
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        $table = $this->table('volleyball_teams');
        
        $table->removeColumn('type_volleyball');
        $table->removeColumn('reference_inscription');
        $table->removeColumn('volleyball_category_id');
        $table->removeColumn('volleyball_district_id');
        $table->removeColumn('volleyball_organisation_id');
        $table->removeColumn('responsable_nom_complet');
        $table->removeColumn('responsable_date_naissance');
        $table->removeColumn('responsable_tel');
        $table->removeColumn('responsable_whatsapp');
        $table->removeColumn('responsable_cin_recto');
        $table->removeColumn('responsable_cin_verso');
        $table->removeColumn('entraineur_nom_complet');
        $table->removeColumn('entraineur_date_naissance');
        $table->removeColumn('entraineur_tel');
        $table->removeColumn('entraineur_whatsapp');
        $table->removeColumn('entraineur_cin_recto');
        $table->removeColumn('entraineur_cin_verso');
        $table->removeColumn('entraineur_same_as_responsable');
        $table->removeColumn('accepter_reglement');
        
        $table->update();
    }
}