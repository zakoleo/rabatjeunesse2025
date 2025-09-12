<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BasketballCategoriesTypesFixture
 */
class BasketballCategoriesTypesFixture extends TestFixture
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
                'basketball_category_id' => 1,
                'basketball_type_id' => 1,
                'created' => '2025-09-11 14:51:14',
                'modified' => '2025-09-11 14:51:14',
            ],
        ];
        parent::init();
    }
}
