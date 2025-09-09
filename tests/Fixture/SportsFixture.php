<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SportsFixture
 */
class SportsFixture extends TestFixture
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
                'code' => 'Lorem ipsum dolor ',
                'min_players' => 1,
                'max_players' => 1,
                'active' => 1,
                'created' => '2025-09-04 10:19:41',
                'modified' => '2025-09-04 10:19:41',
            ],
        ];
        parent::init();
    }
}
