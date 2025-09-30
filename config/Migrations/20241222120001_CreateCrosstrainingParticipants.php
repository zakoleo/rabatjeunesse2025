<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateCrosstrainingParticipants extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('crosstraining_participants');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('category_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('nom_complet', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('date_naissance', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('lieu_naissance', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('gender', 'string', [
            'default' => null,
            'limit' => 10,
            'null' => false,
        ]);
        $table->addColumn('cin', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
        ]);
        $table->addColumn('telephone', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('adresse', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('taille_tshirt', 'string', [
            'default' => null,
            'limit' => 5,
            'null' => false,
        ]);
        $table->addColumn('photo', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('cin_recto', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('cin_verso', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('certificat_medical', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('reference_inscription', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        $table->addColumn('status', 'string', [
            'default' => 'pending',
            'limit' => 10,
            'null' => false,
        ]);
        $table->addColumn('verified_at', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('verified_by', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('verification_notes', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        
        $table->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT']);
        $table->addForeignKey('category_id', 'crosstraining_categories', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT']);
        $table->addForeignKey('verified_by', 'users', 'id', ['delete' => 'SET_NULL', 'update' => 'RESTRICT']);
        
        $table->addIndex(['reference_inscription'], ['unique' => true]);
        $table->addIndex(['status']);
        $table->addIndex(['user_id']);
        $table->addIndex(['category_id']);
        
        $table->create();
    }
}