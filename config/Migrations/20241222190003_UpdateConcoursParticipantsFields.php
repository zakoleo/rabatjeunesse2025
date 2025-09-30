<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateConcoursParticipantsFields extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('concours_participants');
        
        // Add whatsapp column
        $table->addColumn('whatsapp', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
            'after' => 'telephone'
        ]);
        
        // Remove unnecessary columns
        $table->removeColumn('lieu_naissance');
        $table->removeColumn('adresse');
        $table->removeColumn('photo');
        
        // Update cin to be required
        $table->changeColumn('cin', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false
        ]);
        
        $table->update();
    }
}