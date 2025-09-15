<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateClothingSizeEnumConstraints extends BaseMigration
{
    /**
     * Change Method.
     *
     * Updates clothing size ENUM constraints to include XXXL in all player tables.
     * @return void
     */
    public function change(): void
    {
        // List of tables that might have taille_vestimentaire ENUM constraints
        $tables = [
            'basketball_teams_joueurs',
            'handball_teams_joueurs', 
            'volleyball_teams_joueurs',
            'beachvolley_teams_joueurs',
            'football_teams_joueurs',
            'joueurs'
        ];
        
        foreach ($tables as $table) {
            // Check if table exists and has the column
            $tableExists = $this->hasTable($table);
            if ($tableExists && $this->getAdapter()->hasColumn($table, 'taille_vestimentaire')) {
                try {
                    $this->execute("
                        ALTER TABLE {$table} 
                        MODIFY COLUMN taille_vestimentaire ENUM('XS','S','M','L','XL','XXL','XXXL') NOT NULL
                    ");
                    echo "Updated ENUM constraint for table: {$table}\n";
                } catch (Exception $e) {
                    echo "Could not update {$table}: " . $e->getMessage() . "\n";
                    // Continue with other tables
                }
            } else {
                echo "Skipping table {$table} (doesn't exist or doesn't have taille_vestimentaire column)\n";
            }
        }
    }
}
