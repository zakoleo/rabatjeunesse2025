<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class SeedCrosstrainingCategories extends BaseMigration
{
    public function up(): void
    {
        $data = [
            [
                'name' => 'Cross Training U18 Homme',
                'gender' => 'Homme',
                'age_category' => 'U18',
                'date_range_start' => '2007-01-01',
                'date_range_end' => '2025-12-31',
                'min_players' => 1,
                'max_players' => 1,
                'active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Cross Training 18+ Homme',
                'gender' => 'Homme',
                'age_category' => '18+',
                'date_range_start' => '1900-01-01',
                'date_range_end' => '2006-12-31',
                'min_players' => 1,
                'max_players' => 1,
                'active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Cross Training U18 Femme',
                'gender' => 'Femme',
                'age_category' => 'U18',
                'date_range_start' => '2007-01-01',
                'date_range_end' => '2025-12-31',
                'min_players' => 1,
                'max_players' => 1,
                'active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Cross Training 18+ Femme',
                'gender' => 'Femme',
                'age_category' => '18+',
                'date_range_start' => '1900-01-01',
                'date_range_end' => '2006-12-31',
                'min_players' => 1,
                'max_players' => 1,
                'active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ]
        ];

        $table = $this->table('crosstraining_categories');
        $table->insert($data)->save();
    }

    public function down(): void
    {
        $this->execute('DELETE FROM crosstraining_categories');
    }
}