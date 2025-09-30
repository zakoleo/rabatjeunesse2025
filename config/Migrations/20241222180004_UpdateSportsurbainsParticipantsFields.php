<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateSportsurbainsParticipantsFields extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('sportsurbains_participants');
        
        // Add whatsapp column
        $table->addColumn('whatsapp', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => true,
            'after' => 'telephone'
        ]);
        
        // Remove adresse column
        $table->removeColumn('adresse');
        
        // Update cin to be required
        $table->changeColumn('cin', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false
        ]);
        
        $table->update();
    }
}