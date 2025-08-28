<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixBasketballConstraintEmergency extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function up(): void
    {
        // Disable foreign key checks
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        
        // Check what constraints exist and drop them
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
            echo "Dropping constraint: {$constraintName}\n";
            $this->execute("ALTER TABLE basketball_teams DROP FOREIGN KEY {$constraintName}");
        }
        
        // Also try to drop any known problematic constraint names
        $possibleConstraints = ['basketball_teams_ibfk_6', 'basketball_teams_category_fk'];
        foreach ($possibleConstraints as $constraint) {
            try {
                $this->execute("ALTER TABLE basketball_teams DROP FOREIGN KEY {$constraint}");
                echo "Successfully dropped constraint: {$constraint}\n";
            } catch (Exception $e) {
                echo "Constraint {$constraint} doesn't exist or already dropped\n";
            }
        }
        
        // Add the correct constraint
        $this->execute('
            ALTER TABLE basketball_teams 
            ADD CONSTRAINT basketball_teams_category_fk_correct 
            FOREIGN KEY (basketball_category_id) 
            REFERENCES basketball_categories(id) 
            ON DELETE SET NULL ON UPDATE CASCADE
        ');
        
        // Re-enable foreign key checks
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        
        echo "Fixed basketball_teams constraint - now points to basketball_categories\n";
    }

    public function down(): void
    {
        // Disable foreign key checks
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        
        // Drop the correct constraint
        $this->execute('ALTER TABLE basketball_teams DROP FOREIGN KEY basketball_teams_category_fk_correct');
        
        // Add back the incorrect constraint (not recommended)
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
