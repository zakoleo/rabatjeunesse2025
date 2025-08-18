<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TeamsFixture
 */
class TeamsFixture extends TestFixture
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
                'type_football' => 'Lorem ip',
                'district' => 'Lorem ipsum dolor sit amet',
                'organisation' => 'Lorem ipsum dolor ',
                'adresse' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'user_id' => 1,
                'created' => '2025-08-09 10:51:25',
                'modified' => '2025-08-09 10:51:25',
            ],
        ];
        parent::init();
    }
}
