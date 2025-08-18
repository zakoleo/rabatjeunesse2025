<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateFootballCategories extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // First, delete existing categories
        $this->execute('DELETE FROM football_categories');
        
        // Insert new categories with proper format
        $now = date('Y-m-d H:i:s');
        $categories = [
            ['name' => 'U12', 'age_range' => 'Moins de 12 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'U15', 'age_range' => '12-15 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'U18', 'age_range' => '15-18 ans', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => '18+', 'age_range' => '18 ans et plus', 'active' => true, 'created' => $now, 'modified' => $now]
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
        // Restore old categories if needed
        $this->execute('DELETE FROM football_categories');
        
        $now = date('Y-m-d H:i:s');
        $categories = [
            ['name' => 'Moins de 13 ans', 'age_range' => '10-13', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Moins de 15 ans', 'age_range' => '13-15', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Moins de 17 ans', 'age_range' => '15-17', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Plus de 17 ans', 'age_range' => '17+', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Seniors', 'age_range' => '18+', 'active' => true, 'created' => $now, 'modified' => $now]
        ];
        
        $this->table('football_categories')->insert($categories)->save();
    }
}