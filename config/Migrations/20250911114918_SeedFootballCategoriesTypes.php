<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class SeedFootballCategoriesTypes extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        // First, let's check what categories and types exist
        // Note: Adjust these IDs based on your actual database data
        
        $data = [
            // Example relationships - you'll need to adjust these IDs
            // based on your actual football_categories and football_types table IDs
            
            // Format: [category_id, type_id, description]
            // You can find the actual IDs by querying your database:
            // SELECT id, name FROM football_categories;
            // SELECT id, name, code FROM football_types;
            
            // Younger age categories - restricted to smaller field formats
            [1, 2, 'created' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')], // Example: Category 1 → Type 2
            
            // Example relationships (adjust IDs):
            // [category_id_for_minus_12, type_id_for_6x6] - Only 6x6 for -12 year olds
            // [category_id_for_minus_15, type_id_for_6x6] - 6x6 for -15 year olds  
            // [category_id_for_minus_15, type_id_for_11x11] - 11x11 for -15 year olds
            // [category_id_for_senior, type_id_for_5x5] - All formats for seniors
            // [category_id_for_senior, type_id_for_6x6]
            // [category_id_for_senior, type_id_for_11x11]
        ];
        
        // Insert will be commented out until you set the correct IDs
        // Uncomment and run migration after setting correct IDs:
        
        /*
        $table = $this->table('football_categories_types');
        $table->insert($data)->save();
        */
        
        // TO USE THIS MIGRATION:
        // 1. Run: SELECT id, name FROM football_categories;
        // 2. Run: SELECT id, code, name FROM football_types;  
        // 3. Update the $data array above with correct IDs
        // 4. Uncomment the insert code
        // 5. Run: php bin/cake.php migrations migrate
    }
}
