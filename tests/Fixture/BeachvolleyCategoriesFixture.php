<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BeachvolleyCategoriesFixture
 */
class BeachvolleyCategoriesFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'age_range' => 'Lorem ipsum dolor sit amet',
                'min_date' => '2025-08-28',
                'max_date' => '2025-08-28',
                'min_birth_year' => 1,
                'max_birth_year' => 1,
                'active' => 1,
                'created' => 1756378758,
                'updated' => 1756378758,
            ],
        ];
        parent::init();
    }
}
