<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddResponsableEntraineurFieldsToTeams extends BaseMigration
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
        $table->addColumn('responsable_nom_complet', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('responsable_date_naissance', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('responsable_tel', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('responsable_whatsapp', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('responsable_cin_recto', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('responsable_cin_verso', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('entraineur_nom_complet', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('entraineur_date_naissance', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('entraineur_tel', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('entraineur_whatsapp', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('entraineur_cin_recto', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('entraineur_cin_verso', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('entraineur_same_as_responsable', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->update();
    }
}
