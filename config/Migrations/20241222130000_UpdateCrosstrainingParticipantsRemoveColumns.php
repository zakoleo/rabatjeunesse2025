<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateCrosstrainingParticipantsRemoveColumns extends BaseMigration
{
    public function change(): void
    {
        $table = $this->table('crosstraining_participants');
        
        // Remove unused columns
        $table->removeColumn('lieu_naissance');
        $table->removeColumn('photo');
        $table->removeColumn('certificat_medical');
        
        $table->update();
    }
}