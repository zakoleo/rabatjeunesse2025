<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FootballDistrictsFixture
 */
class FootballDistrictsFixture extends TestFixture
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
                'active' => 1,
                'created' => '2025-08-10 08:40:12',
                'modified' => '2025-08-10 08:40:12',
            ],
        ];
        parent::init();
    }
}
