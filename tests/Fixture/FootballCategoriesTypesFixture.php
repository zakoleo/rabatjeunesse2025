<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FootballCategoriesTypesFixture
 */
class FootballCategoriesTypesFixture extends TestFixture
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
                'football_category_id' => 1,
                'football_type_id' => 1,
                'created' => '2025-09-11 11:50:04',
                'modified' => '2025-09-11 11:50:04',
            ],
        ];
        parent::init();
    }
}
