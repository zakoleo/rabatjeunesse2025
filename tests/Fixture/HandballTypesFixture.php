<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HandballTypesFixture
 */
class HandballTypesFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor ',
                'code' => 'Lorem ip',
                'min_players' => 1,
                'max_players' => 1,
                'active' => 1,
                'created' => '2025-09-11 14:49:25',
                'modified' => '2025-09-11 14:49:25',
            ],
        ];
        parent::init();
    }
}
