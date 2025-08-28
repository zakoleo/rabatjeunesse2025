<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixBeachvolleyTeamsForeignKeys extends BaseMigration
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
        // Check if column exists first
        $columnResult = $this->fetchRow("SELECT COLUMN_NAME FROM information_schema.COLUMNS 
                                        WHERE TABLE_SCHEMA = 'rabatjeunesse' 
                                        AND TABLE_NAME = 'beachvolley_teams' 
                                        AND COLUMN_NAME = 'beachvolley_category_id'");
        
        if (!$columnResult) {
            // Column doesn't exist, skip this migration
            return;
        }
        
        // Check if foreign key exists before dropping
        $result = $this->fetchRow("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
                                  WHERE TABLE_SCHEMA = 'rabatjeunesse' 
                                  AND TABLE_NAME = 'beachvolley_teams' 
                                  AND COLUMN_NAME = 'beachvolley_category_id' 
                                  AND REFERENCED_TABLE_NAME IS NOT NULL");
        
        if ($result) {
            $this->execute("ALTER TABLE beachvolley_teams DROP FOREIGN KEY {$result['CONSTRAINT_NAME']}");
        }
        
        // Add new foreign key constraint pointing to beachvolley_categories
        $this->execute('ALTER TABLE beachvolley_teams ADD CONSTRAINT beachvolley_teams_beachvolley_category_fk 
                       FOREIGN KEY (beachvolley_category_id) REFERENCES beachvolley_categories(id) ON DELETE SET NULL');
    }

    public function down()
    {
        // Drop the new foreign key constraint
        $this->execute('ALTER TABLE beachvolley_teams DROP FOREIGN KEY beachvolley_teams_beachvolley_category_fk');
    }
}
