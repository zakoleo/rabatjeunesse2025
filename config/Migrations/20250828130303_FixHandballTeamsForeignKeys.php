<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixHandballTeamsForeignKeys extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        // Check if foreign key exists before dropping
        $result = $this->fetchRow("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
                                  WHERE TABLE_SCHEMA = 'rabatjeunesse' 
                                  AND TABLE_NAME = 'handball_teams' 
                                  AND COLUMN_NAME = 'handball_category_id' 
                                  AND REFERENCED_TABLE_NAME IS NOT NULL");
        
        if ($result) {
            $this->execute("ALTER TABLE handball_teams DROP FOREIGN KEY {$result['CONSTRAINT_NAME']}");
        }
        
        // Add new foreign key constraint pointing to handball_categories
        $this->execute('ALTER TABLE handball_teams ADD CONSTRAINT handball_teams_handball_category_fk 
                       FOREIGN KEY (handball_category_id) REFERENCES handball_categories(id) ON DELETE SET NULL');
    }

    public function down()
    {
        // Drop the new foreign key constraint
        $this->execute('ALTER TABLE handball_teams DROP FOREIGN KEY handball_teams_handball_category_fk');
        
        // Restore the old foreign key constraint
        $this->execute('ALTER TABLE handball_teams ADD CONSTRAINT handball_teams_ibfk_2 
                       FOREIGN KEY (handball_category_id) REFERENCES football_categories(id) ON DELETE SET NULL');
    }
}
