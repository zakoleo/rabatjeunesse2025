<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CategoriesFixture
 */
class CategoriesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'sport_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'age_range' => 'Lorem ipsum dolor sit amet',
                'min_birth_year' => 1,
                'max_birth_year' => 1,
                'min_birth_date' => '2025-09-04',
                'max_birth_date' => '2025-09-04',
                'active' => 1,
                'created' => '2025-09-04 10:19:52',
                'modified' => '2025-09-04 10:19:52',
            ],
        ];
        parent::init();
    }
}
