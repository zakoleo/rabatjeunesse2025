<?php
/**
 * Simple script to create football category-type relationships
 */

// Include CakePHP bootstrap
require 'vendor/autoload.php';

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

// Bootstrap CakePHP
$bootstrap = dirname(__FILE__) . '/config/bootstrap.php';
require $bootstrap;

try {
    echo "🔗 CREATING FOOTBALL CATEGORY-TYPE RELATIONSHIPS...\n\n";
    
    // Get the database connection
    $connection = ConnectionManager::get('default');
    
    // Clear existing relationships
    echo "🧹 Clearing existing relationships...\n";
    $connection->execute('DELETE FROM football_categories_types');
    
    // Define relationships directly with IDs (we'll look them up)
    $relationships = [
        '-12' => ['6x6'],                    // -12 can only play 6x6 (smaller field)
        '-15' => ['6x6', '11x11'],          // -15 can play 6x6 and 11x11 (transitional)
        '-18' => ['5x5', '6x6', '11x11'],   // -18 can play all formats
        'Senior' => ['5x5', '6x6', '11x11'] // Senior can play all formats
    ];
    
    foreach ($relationships as $categoryName => $allowedTypes) {
        echo "📋 Processing category: $categoryName\n";
        
        // Get category ID
        $categoryQuery = $connection->execute('SELECT id FROM football_categories WHERE name = ?', [$categoryName]);
        $categoryRow = $categoryQuery->fetch();
        
        if (!$categoryRow) {
            echo "  ❌ Category '$categoryName' not found!\n";
            continue;
        }
        
        $categoryId = $categoryRow[0]; // First column
        echo "  ✓ Found category ID: $categoryId\n";
        
        foreach ($allowedTypes as $typeCode) {
            // Get type ID
            $typeQuery = $connection->execute('SELECT id FROM football_types WHERE code = ?', [$typeCode]);
            $typeRow = $typeQuery->fetch();
            
            if (!$typeRow) {
                echo "    ❌ Type '$typeCode' not found!\n";
                continue;
            }
            
            $typeId = $typeRow[0]; // First column
            echo "    ✓ Found type ID: $typeId for code: $typeCode\n";
            
            // Insert relationship
            try {
                $connection->execute(
                    'INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified) 
                     VALUES (?, ?, NOW(), NOW())',
                    [$categoryId, $typeId]
                );
                echo "    ✅ Created relationship: $categoryName → $typeCode\n";
            } catch (Exception $e) {
                echo "    ❌ Error creating relationship: " . $e->getMessage() . "\n";
            }
        }
        echo "\n";
    }
    
    // Verify the relationships
    echo "📊 VERIFICATION - Current relationships:\n";
    $verifyQuery = $connection->execute('
        SELECT fc.name as category_name, ft.code as type_code, ft.name as type_name
        FROM football_categories_types fct
        JOIN football_categories fc ON fct.football_category_id = fc.id  
        JOIN football_types ft ON fct.football_type_id = ft.id
        ORDER BY fc.name, ft.code
    ');
    
    $count = 0;
    while ($row = $verifyQuery->fetch()) {
        echo "  {$row[0]} → {$row[1]} ({$row[2]})\n"; // Using numeric indexes
        $count++;
    }
    
    if ($count === 0) {
        echo "  ❌ No relationships found!\n";
    } else {
        echo "\n✅ Successfully created $count relationships!\n";
        echo "\n💡 Now test your form - when you select '-12', you should only see '6x6' type available.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>