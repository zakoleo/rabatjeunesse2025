<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class SeedFootballReferenceTables extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        $now = date('Y-m-d H:i:s');
        
        // Insert football categories
        $categories = [
            ['name' => 'Moins de 13 ans', 'age_range' => '10-13', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Moins de 15 ans', 'age_range' => '13-15', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Moins de 17 ans', 'age_range' => '15-17', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Plus de 17 ans', 'age_range' => '17+', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Seniors', 'age_range' => '18+', 'active' => true, 'created' => $now, 'modified' => $now]
        ];
        
        $this->table('football_categories')->insert($categories)->save();
        
        // Insert football districts
        $districts = [
            ['name' => 'Agdal', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Hassan', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Souissi', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Yacoub El Mansour', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Océan', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Hay Riad', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Takadoum', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Hay Nahda', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Autre', 'active' => true, 'created' => $now, 'modified' => $now]
        ];
        
        $this->table('football_districts')->insert($districts)->save();
        
        // Insert football organisations
        $organisations = [
            ['name' => 'École', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Lycée', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Université', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Association sportive', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Club', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Entreprise', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Groupe d\'amis', 'active' => true, 'created' => $now, 'modified' => $now],
            ['name' => 'Autre', 'active' => true, 'created' => $now, 'modified' => $now]
        ];
        
        $this->table('football_organisations')->insert($organisations)->save();
    }
    
    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        $this->execute('DELETE FROM football_categories');
        $this->execute('DELETE FROM football_districts');
        $this->execute('DELETE FROM football_organisations');
    }
}
