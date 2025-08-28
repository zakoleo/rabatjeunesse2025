<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CleanupInvalidForeignKeys extends BaseMigration
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
        // Set invalid foreign key values to NULL for all sports tables
        // Only update columns that exist
        
        // Check if basketball_category_id column exists in basketball_teams
        $result = $this->fetchRow("SELECT COLUMN_NAME FROM information_schema.COLUMNS 
                                  WHERE TABLE_SCHEMA = 'rabatjeunesse' 
                                  AND TABLE_NAME = 'basketball_teams' 
                                  AND COLUMN_NAME = 'basketball_category_id'");
        if ($result) {
            $this->execute('UPDATE basketball_teams SET basketball_category_id = NULL 
                           WHERE basketball_category_id IS NOT NULL 
                           AND basketball_category_id NOT IN (SELECT id FROM basketball_categories)');
        }
        
        // Check if handball_category_id column exists in handball_teams
        $result = $this->fetchRow("SELECT COLUMN_NAME FROM information_schema.COLUMNS 
                                  WHERE TABLE_SCHEMA = 'rabatjeunesse' 
                                  AND TABLE_NAME = 'handball_teams' 
                                  AND COLUMN_NAME = 'handball_category_id'");
        if ($result) {
            $this->execute('UPDATE handball_teams SET handball_category_id = NULL 
                           WHERE handball_category_id IS NOT NULL 
                           AND handball_category_id NOT IN (SELECT id FROM handball_categories)');
        }
        
        // Check if volleyball_category_id column exists in volleyball_teams
        $result = $this->fetchRow("SELECT COLUMN_NAME FROM information_schema.COLUMNS 
                                  WHERE TABLE_SCHEMA = 'rabatjeunesse' 
                                  AND TABLE_NAME = 'volleyball_teams' 
                                  AND COLUMN_NAME = 'volleyball_category_id'");
        if ($result) {
            $this->execute('UPDATE volleyball_teams SET volleyball_category_id = NULL 
                           WHERE volleyball_category_id IS NOT NULL 
                           AND volleyball_category_id NOT IN (SELECT id FROM volleyball_categories)');
        }
        
        // Check if beachvolley_category_id column exists in beachvolley_teams
        $result = $this->fetchRow("SELECT COLUMN_NAME FROM information_schema.COLUMNS 
                                  WHERE TABLE_SCHEMA = 'rabatjeunesse' 
                                  AND TABLE_NAME = 'beachvolley_teams' 
                                  AND COLUMN_NAME = 'beachvolley_category_id'");
        if ($result) {
            $this->execute('UPDATE beachvolley_teams SET beachvolley_category_id = NULL 
                           WHERE beachvolley_category_id IS NOT NULL 
                           AND beachvolley_category_id NOT IN (SELECT id FROM beachvolley_categories)');
        }
    }

    public function down()
    {
        // This migration cannot be reversed as we're cleaning up invalid data
    }
}
