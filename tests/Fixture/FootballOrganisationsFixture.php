<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FootballOrganisationsFixture
 */
class FootballOrganisationsFixture extends TestFixture
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
                'created' => '2025-08-10 08:42:14',
                'modified' => '2025-08-10 08:42:14',
            ],
        ];
        parent::init();
    }
}
