<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class PopulateFootballCategoriesDateRanges extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Update date ranges for football categories based on current year
        $currentYear = date('Y');
        
        // Update categories with date ranges
        $this->execute("UPDATE football_categories SET 
            min_date = '2014-01-01', 
            max_date = '2015-12-31',
            min_birth_year = 2014,
            max_birth_year = 2015
            WHERE name = '-12'");
            
        $this->execute("UPDATE football_categories SET 
            min_date = '2012-01-01', 
            max_date = '2013-12-31',
            min_birth_year = 2012,
            max_birth_year = 2013
            WHERE name = '-15'");
            
        $this->execute("UPDATE football_categories SET 
            min_date = '2008-01-01', 
            max_date = '2010-12-31',
            min_birth_year = 2008,
            max_birth_year = 2010
            WHERE name = '-18'");
            
        $this->execute("UPDATE football_categories SET 
            min_date = '1970-01-01', 
            max_date = '2007-12-31',
            min_birth_year = 1970,
            max_birth_year = 2007
            WHERE name = '+18'");
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        // Clear date ranges
        $this->execute("UPDATE football_categories SET 
            min_date = NULL, 
            max_date = NULL,
            min_birth_year = NULL,
            max_birth_year = NULL");
    }
}
