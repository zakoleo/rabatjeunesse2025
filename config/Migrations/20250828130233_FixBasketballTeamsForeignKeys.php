<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixBasketballTeamsForeignKeys extends BaseMigration
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
        // Drop existing foreign key constraint for basketball_category_id
        $this->execute('ALTER TABLE basketball_teams DROP FOREIGN KEY basketball_teams_ibfk_2');
        
        // Add new foreign key constraint pointing to basketball_categories
        $this->execute('ALTER TABLE basketball_teams ADD CONSTRAINT basketball_teams_basketball_category_fk 
                       FOREIGN KEY (basketball_category_id) REFERENCES basketball_categories(id) ON DELETE SET NULL');
    }

    public function down()
    {
        // Drop the new foreign key constraint
        $this->execute('ALTER TABLE basketball_teams DROP FOREIGN KEY basketball_teams_basketball_category_fk');
        
        // Restore the old foreign key constraint (though this might fail if data is inconsistent)
        $this->execute('ALTER TABLE basketball_teams ADD CONSTRAINT basketball_teams_ibfk_2 
                       FOREIGN KEY (basketball_category_id) REFERENCES football_categories(id) ON DELETE SET NULL');
    }
}
