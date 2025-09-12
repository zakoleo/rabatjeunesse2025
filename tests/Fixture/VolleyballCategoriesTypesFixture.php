<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VolleyballCategoriesTypesFixture
 */
class VolleyballCategoriesTypesFixture extends TestFixture
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
                'volleyball_category_id' => 1,
                'volleyball_type_id' => 1,
                'created' => '2025-09-11 14:50:30',
                'modified' => '2025-09-11 14:50:30',
            ],
        ];
        parent::init();
    }
}
