<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class PopulateAllCategoryTables extends BaseMigration
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
        // Basketball Categories
        $basketballData = [
            [
                'name' => '-15',
                'age_range' => '-15 ans',
                'min_date' => '2010-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2010,
                'max_birth_year' => 2025,
                'active' => 1
            ],
            [
                'name' => '-17',
                'age_range' => '-17 ans',
                'min_date' => '2008-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2008,
                'max_birth_year' => 2025,
                'active' => 1,
            ],
            [
                'name' => '-21',
                'age_range' => '-21 ans',
                'min_date' => '2004-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2004,
                'max_birth_year' => 2025,
                'active' => 1,
            ],
            [
                'name' => '+21',
                'age_range' => '+21 ans',
                'min_date' => '1970-01-01',
                'max_date' => '2003-12-31',
                'min_birth_year' => 1970,
                'max_birth_year' => 2003,
                'active' => 1,
            ]
        ];

        // Handball Categories
        $handballData = [
            [
                'name' => '-15',
                'age_range' => '-15 ans',
                'min_date' => '2010-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2010,
                'max_birth_year' => 2025,
                'active' => 1,
            ],
            [
                'name' => '-17',
                'age_range' => '-17 ans',
                'min_date' => '2008-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2008,
                'max_birth_year' => 2025,
                'active' => 1,
            ],
            [
                'name' => '-19',
                'age_range' => '-19 ans',
                'min_date' => '2006-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2006,
                'max_birth_year' => 2025,
                'active' => 1,
            ]
        ];

        // Volleyball Categories
        $volleyballData = [
            [
                'name' => '-15',
                'age_range' => '-15 ans',
                'min_date' => '2010-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2010,
                'max_birth_year' => 2025,
                'active' => 1,
            ],
            [
                'name' => '-17',
                'age_range' => '-17 ans',
                'min_date' => '2008-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2008,
                'max_birth_year' => 2025,
                'active' => 1,
            ],
            [
                'name' => '-19',
                'age_range' => '-19 ans',
                'min_date' => '2006-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2006,
                'max_birth_year' => 2025,
                'active' => 1,
            ]
        ];

        // Beachvolley Categories
        $beachvolleyData = [
            [
                'name' => '-17',
                'age_range' => '-17 ans',
                'min_date' => '2008-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2008,
                'max_birth_year' => 2025,
                'active' => 1,
            ],
            [
                'name' => '-21',
                'age_range' => '-21 ans',
                'min_date' => '2004-01-01',
                'max_date' => '2025-12-31',
                'min_birth_year' => 2004,
                'max_birth_year' => 2025,
                'active' => 1,
            ],
            [
                'name' => '+21',
                'age_range' => '+21 ans',
                'min_date' => '1970-01-01',
                'max_date' => '2003-12-31',
                'min_birth_year' => 1970,
                'max_birth_year' => 2003,
                'active' => 1,
            ]
        ];

        // Insert data
        $this->table('basketball_categories')->insert($basketballData)->save();
        $this->table('handball_categories')->insert($handballData)->save();
        $this->table('volleyball_categories')->insert($volleyballData)->save();
        $this->table('beachvolley_categories')->insert($beachvolleyData)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM basketball_categories');
        $this->execute('DELETE FROM handball_categories');
        $this->execute('DELETE FROM volleyball_categories');
        $this->execute('DELETE FROM beachvolley_categories');
    }
}
