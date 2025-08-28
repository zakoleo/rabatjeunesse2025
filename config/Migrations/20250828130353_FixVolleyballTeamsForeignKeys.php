<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixVolleyballTeamsForeignKeys extends BaseMigration
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
                                  AND TABLE_NAME = 'volleyball_teams' 
                                  AND COLUMN_NAME = 'volleyball_category_id' 
                                  AND REFERENCED_TABLE_NAME IS NOT NULL");
        
        if ($result) {
            $this->execute("ALTER TABLE volleyball_teams DROP FOREIGN KEY {$result['CONSTRAINT_NAME']}");
        }
        
        // Add new foreign key constraint pointing to volleyball_categories
        $this->execute('ALTER TABLE volleyball_teams ADD CONSTRAINT volleyball_teams_volleyball_category_fk 
                       FOREIGN KEY (volleyball_category_id) REFERENCES volleyball_categories(id) ON DELETE SET NULL');
    }

    public function down()
    {
        // Drop the new foreign key constraint
        $this->execute('ALTER TABLE volleyball_teams DROP FOREIGN KEY volleyball_teams_volleyball_category_fk');
    }
}
