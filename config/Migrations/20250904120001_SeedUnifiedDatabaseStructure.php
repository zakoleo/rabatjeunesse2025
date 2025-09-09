<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class SeedUnifiedDatabaseStructure extends BaseMigration
{
    /**
     * Up Method - Seed the reference tables
     */
    public function up(): void
    {
        // 1. Seed Sports table
        $sportsData = [
            ['name' => 'Football', 'code' => 'football', 'min_players' => 5, 'max_players' => 18, 'active' => true],
            ['name' => 'Basketball', 'code' => 'basketball', 'min_players' => 5, 'max_players' => 12, 'active' => true],
            ['name' => 'Handball', 'code' => 'handball', 'min_players' => 7, 'max_players' => 14, 'active' => true],
            ['name' => 'Volleyball', 'code' => 'volleyball', 'min_players' => 6, 'max_players' => 12, 'active' => true],
            ['name' => 'Beach Volleyball', 'code' => 'beachvolley', 'min_players' => 2, 'max_players' => 4, 'active' => true],
        ];
        $this->table('sports')->insert($sportsData)->save();

        // 2. Seed Football types table
        $footballTypesData = [
            ['name' => 'Football à 5 (5x5)', 'code' => '5x5', 'min_players' => 5, 'max_players' => 10, 'active' => true],
            ['name' => 'Football à 6 (6x6)', 'code' => '6x6', 'min_players' => 6, 'max_players' => 12, 'active' => true],
            ['name' => 'Football à 11 (11x11)', 'code' => '11x11', 'min_players' => 11, 'max_players' => 18, 'active' => true],
        ];
        $this->table('football_types')->insert($footballTypesData)->save();

        // 3. Seed Districts table
        $districtsData = [
            ['name' => 'Agdal Riyad', 'code' => 'agdal_riyad', 'active' => true],
            ['name' => 'Hassan', 'code' => 'hassan', 'active' => true],
            ['name' => 'Souissi', 'code' => 'souissi', 'active' => true],
            ['name' => 'Yacoub El Mansour', 'code' => 'yacoub_el_mansour', 'active' => true],
            ['name' => 'El Youssoufia', 'code' => 'el_youssoufia', 'active' => true],
            ['name' => 'Hay Riad', 'code' => 'hay_riad', 'active' => true],
        ];
        $this->table('districts')->insert($districtsData)->save();

        // 4. Seed Organizations table
        $organizationsData = [
            ['name' => 'Association', 'code' => 'association', 'active' => true],
            ['name' => 'Club', 'code' => 'club', 'active' => true],
            ['name' => 'École', 'code' => 'ecole', 'active' => true],
            ['name' => 'Quartier', 'code' => 'quartier', 'active' => true],
        ];
        $this->table('organizations')->insert($organizationsData)->save();

        // 5. Seed Role types table
        $roleTypesData = [
            ['name' => 'Responsable', 'code' => 'manager', 'active' => true],
            ['name' => 'Entraîneur', 'code' => 'coach', 'active' => true],
            ['name' => 'Joueur', 'code' => 'player', 'active' => true],
        ];
        $this->table('role_types')->insert($roleTypesData)->save();

        // 6. Seed Categories for Football (sport_id = 1)
        $footballCategoriesData = [
            [
                'sport_id' => 1, 
                'name' => '-12', 
                'age_range' => '-12 ans', 
                'min_birth_year' => 2014, 
                'max_birth_year' => 2015, 
                'min_birth_date' => '2014-01-01', 
                'max_birth_date' => '2015-12-31',
                'active' => true
            ],
            [
                'sport_id' => 1, 
                'name' => '-15', 
                'age_range' => '-15 ans', 
                'min_birth_year' => 2011, 
                'max_birth_year' => 2012, 
                'min_birth_date' => '2011-01-01', 
                'max_birth_date' => '2012-12-31',
                'active' => true
            ],
            [
                'sport_id' => 1, 
                'name' => 'U17', 
                'age_range' => 'U17', 
                'min_birth_year' => 2009, 
                'max_birth_year' => 2010, 
                'min_birth_date' => '2009-01-01', 
                'max_birth_date' => '2010-12-31',
                'active' => true
            ],
            [
                'sport_id' => 1, 
                'name' => 'Senior', 
                'age_range' => 'Senior', 
                'min_birth_year' => 1980, 
                'max_birth_year' => 2008, 
                'min_birth_date' => '1980-01-01', 
                'max_birth_date' => '2008-12-31',
                'active' => true
            ],
        ];
        $this->table('categories')->insert($footballCategoriesData)->save();

        // 7. Seed Categories for Basketball (sport_id = 2)
        $basketballCategoriesData = [
            [
                'sport_id' => 2, 
                'name' => '-12', 
                'age_range' => '-12 ans', 
                'min_birth_year' => 2014, 
                'max_birth_year' => 2015, 
                'min_birth_date' => '2014-01-01', 
                'max_birth_date' => '2015-12-31',
                'active' => true
            ],
            [
                'sport_id' => 2, 
                'name' => '-15', 
                'age_range' => '-15 ans', 
                'min_birth_year' => 2011, 
                'max_birth_year' => 2012, 
                'min_birth_date' => '2011-01-01', 
                'max_birth_date' => '2012-12-31',
                'active' => true
            ],
            [
                'sport_id' => 2, 
                'name' => 'U17', 
                'age_range' => 'U17', 
                'min_birth_year' => 2009, 
                'max_birth_year' => 2010, 
                'min_birth_date' => '2009-01-01', 
                'max_birth_date' => '2010-12-31',
                'active' => true
            ],
            [
                'sport_id' => 2, 
                'name' => 'Senior', 
                'age_range' => 'Senior', 
                'min_birth_year' => 1980, 
                'max_birth_year' => 2008, 
                'min_birth_date' => '1980-01-01', 
                'max_birth_date' => '2008-12-31',
                'active' => true
            ],
        ];
        $this->table('categories')->insert($basketballCategoriesData)->save();

        // 8. Seed Categories for Handball (sport_id = 3)
        $handballCategoriesData = [
            [
                'sport_id' => 3, 
                'name' => '-12', 
                'age_range' => '-12 ans', 
                'min_birth_year' => 2014, 
                'max_birth_year' => 2015, 
                'min_birth_date' => '2014-01-01', 
                'max_birth_date' => '2015-12-31',
                'active' => true
            ],
            [
                'sport_id' => 3, 
                'name' => '-15', 
                'age_range' => '-15 ans', 
                'min_birth_year' => 2011, 
                'max_birth_year' => 2012, 
                'min_birth_date' => '2011-01-01', 
                'max_birth_date' => '2012-12-31',
                'active' => true
            ],
            [
                'sport_id' => 3, 
                'name' => 'U17', 
                'age_range' => 'U17', 
                'min_birth_year' => 2009, 
                'max_birth_year' => 2010, 
                'min_birth_date' => '2009-01-01', 
                'max_birth_date' => '2010-12-31',
                'active' => true
            ],
            [
                'sport_id' => 3, 
                'name' => 'Senior', 
                'age_range' => 'Senior', 
                'min_birth_year' => 1980, 
                'max_birth_year' => 2008, 
                'min_birth_date' => '1980-01-01', 
                'max_birth_date' => '2008-12-31',
                'active' => true
            ],
        ];
        $this->table('categories')->insert($handballCategoriesData)->save();

        // 9. Seed Categories for Volleyball (sport_id = 4)
        $volleyballCategoriesData = [
            [
                'sport_id' => 4, 
                'name' => '-12', 
                'age_range' => '-12 ans', 
                'min_birth_year' => 2014, 
                'max_birth_year' => 2015, 
                'min_birth_date' => '2014-01-01', 
                'max_birth_date' => '2015-12-31',
                'active' => true
            ],
            [
                'sport_id' => 4, 
                'name' => '-15', 
                'age_range' => '-15 ans', 
                'min_birth_year' => 2011, 
                'max_birth_year' => 2012, 
                'min_birth_date' => '2011-01-01', 
                'max_birth_date' => '2012-12-31',
                'active' => true
            ],
            [
                'sport_id' => 4, 
                'name' => 'U17', 
                'age_range' => 'U17', 
                'min_birth_year' => 2009, 
                'max_birth_year' => 2010, 
                'min_birth_date' => '2009-01-01', 
                'max_birth_date' => '2010-12-31',
                'active' => true
            ],
            [
                'sport_id' => 4, 
                'name' => 'Senior', 
                'age_range' => 'Senior', 
                'min_birth_year' => 1980, 
                'max_birth_year' => 2008, 
                'min_birth_date' => '1980-01-01', 
                'max_birth_date' => '2008-12-31',
                'active' => true
            ],
        ];
        $this->table('categories')->insert($volleyballCategoriesData)->save();

        // 10. Seed Categories for Beach Volleyball (sport_id = 5)
        $beachVolleyCategoriesData = [
            [
                'sport_id' => 5, 
                'name' => '-15', 
                'age_range' => '-15 ans', 
                'min_birth_year' => 2011, 
                'max_birth_year' => 2012, 
                'min_birth_date' => '2011-01-01', 
                'max_birth_date' => '2012-12-31',
                'active' => true
            ],
            [
                'sport_id' => 5, 
                'name' => 'U17', 
                'age_range' => 'U17', 
                'min_birth_year' => 2009, 
                'max_birth_year' => 2010, 
                'min_birth_date' => '2009-01-01', 
                'max_birth_date' => '2010-12-31',
                'active' => true
            ],
            [
                'sport_id' => 5, 
                'name' => 'Senior', 
                'age_range' => 'Senior', 
                'min_birth_year' => 1980, 
                'max_birth_year' => 2008, 
                'min_birth_date' => '1980-01-01', 
                'max_birth_date' => '2008-12-31',
                'active' => true
            ],
        ];
        $this->table('categories')->insert($beachVolleyCategoriesData)->save();
    }

    /**
     * Down Method - Remove seed data
     */
    public function down(): void
    {
        // Check if tables exist before trying to delete data
        $tables = ['categories', 'role_types', 'organizations', 'districts', 'football_types', 'sports'];
        
        foreach ($tables as $table) {
            try {
                $this->execute("DELETE FROM $table");
            } catch (Exception $e) {
                // Table might not exist, skip silently
                continue;
            }
        }
    }
}