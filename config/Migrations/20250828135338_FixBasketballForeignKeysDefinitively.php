<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixBasketballForeignKeysDefinitively extends BaseMigration
{
    /**
     * Up Method - Fix all basketball foreign key constraints
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        
        // Check and drop existing foreign key constraints
        // We'll use a query to check what constraints exist first
        
        $result = $this->query("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = 'rabatjprojectsde_db' 
            AND TABLE_NAME = 'basketball_teams' 
            AND COLUMN_NAME = 'basketball_category_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        foreach ($result as $row) {
            $constraintName = $row['CONSTRAINT_NAME'];
            $this->execute("ALTER TABLE basketball_teams DROP FOREIGN KEY {$constraintName}");
            echo "Dropped constraint: {$constraintName}\n";
        }
        
        // Now add the correct foreign key constraint pointing to basketball_categories
        $this->execute('
            ALTER TABLE basketball_teams 
            ADD CONSTRAINT basketball_teams_category_fk 
            FOREIGN KEY (basketball_category_id) 
            REFERENCES basketball_categories(id) 
            ON DELETE SET NULL ON UPDATE CASCADE
        ');
        
        // Re-enable foreign key checks
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        
        echo "Successfully fixed basketball_teams foreign key to point to basketball_categories table\n";
    }

    /**
     * Down Method - Revert changes (for rollback)
     */
    public function down(): void
    {
        // Disable foreign key checks
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        
        // Drop the correct foreign key
        $this->execute('ALTER TABLE basketball_teams DROP FOREIGN KEY basketball_teams_category_fk');
        
        // Add back the incorrect foreign key (though this is not recommended)
        $this->execute('
            ALTER TABLE basketball_teams 
            ADD CONSTRAINT basketball_teams_ibfk_6 
            FOREIGN KEY (basketball_category_id) 
            REFERENCES football_categories(id) 
            ON DELETE SET NULL ON UPDATE NO ACTION
        ');
        
        // Re-enable foreign key checks
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
