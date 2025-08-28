<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateFootballCategoriesToNewFormat extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Update football categories to new format
        $this->execute('DELETE FROM football_categories');
        
        $now = date('Y-m-d H:i:s');
        $categories = [
            ['name' => '-12', 'age_range' => '-12 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => '-15', 'age_range' => '-15 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => '-18', 'age_range' => '-18 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => '+18', 'age_range' => '+18 ans', 'active' => true, 'created' => $now, 'modified' => $now]
        ];
        
        $this->table('football_categories')->insert($categories)->save();
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        // Restore old categories
        $this->execute('DELETE FROM football_categories');
        
        $now = date('Y-m-d H:i:s');
        $categories = [
            ['name' => 'U12', 'age_range' => 'Moins de 12 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'U15', 'age_range' => '12-15 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'U18', 'age_range' => '15-18 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => '18+', 'age_range' => '18 ans et plus', 'active' => true, 'created' => $now, 'modified' => $now]
        ];
        
        $this->table('football_categories')->insert($categories)->save();
    }
}
