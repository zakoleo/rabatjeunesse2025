<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HandballTeamsFixture
 */
class HandballTeamsFixture extends TestFixture
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
                'nom_equipe' => 'Lorem ipsum dolor sit amet',
                'categorie' => 'Lorem ip',
                'genre' => 'Lorem ip',
                'district' => 'Lorem ipsum dolor sit amet',
                'organisation' => 'Lorem ipsum dolor ',
                'adresse' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'user_id' => 1,
                'created' => '2025-08-09 10:52:35',
                'modified' => '2025-08-09 10:52:35',
            ],
        ];
        parent::init();
    }
}
