<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * JoueursFixture
 */
class JoueursFixture extends TestFixture
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
                'nom_complet' => 'Lorem ipsum dolor sit amet',
                'date_naissance' => '2025-08-09',
                'identifiant' => 'Lorem ipsum dolor sit amet',
                'taille_vestimentaire' => 'Lor',
                'team_id' => 1,
                'created' => '2025-08-09 10:52:25',
                'modified' => '2025-08-09 10:52:25',
            ],
        ];
        parent::init();
    }
}
