<?php
/**
 * Add relationships for +19 category to play all football types
 */

// Include CakePHP bootstrap
require 'vendor/autoload.php';

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

// Bootstrap CakePHP
$bootstrap = dirname(__FILE__) . '/config/bootstrap.php';
require $bootstrap;

try {
    echo "🔗 ADDING +19 CATEGORY RELATIONSHIPS...\n\n";
    
    // Get the database connection
    $connection = ConnectionManager::get('default');
    
    // Get +19 category ID
    $categoryQuery = $connection->execute('SELECT id FROM football_categories WHERE name = ?', ['+19']);
    $categoryRow = $categoryQuery->fetch();
    
    if (!$categoryRow) {
        echo "❌ +19 category not found!\n";
        exit(1);
    }
    
    $categoryId = $categoryRow[0];
    echo "✓ Found +19 category ID: $categoryId\n\n";
    
    // Get all football type IDs
    $typesQuery = $connection->execute('SELECT id, code, name FROM football_types WHERE active = 1');
    $types = [];
    while ($typeRow = $typesQuery->fetch()) {
        $types[] = [
            'id' => $typeRow[0],
            'code' => $typeRow[1], 
            'name' => $typeRow[2]
        ];
    }
    
    echo "📋 Found " . count($types) . " active football types:\n";
    foreach ($types as $type) {
        echo "  - {$type['code']} ({$type['name']})\n";
    }
    echo "\n";
    
    // Add relationships for +19 to all types
    foreach ($types as $type) {
        try {
            // Check if relationship already exists
            $existsQuery = $connection->execute(
                'SELECT COUNT(*) FROM football_categories_types WHERE football_category_id = ? AND football_type_id = ?',
                [$categoryId, $type['id']]
            );
            $exists = $existsQuery->fetch()[0];
            
            if ($exists > 0) {
                echo "  ℹ️  +19 → {$type['code']} (already exists)\n";
                continue;
            }
            
            // Insert new relationship
            $connection->execute(
                'INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified) 
                 VALUES (?, ?, NOW(), NOW())',
                [$categoryId, $type['id']]
            );
            echo "  ✅ Added: +19 → {$type['code']} ({$type['name']})\n";
            
        } catch (Exception $e) {
            echo "  ❌ Error adding +19 → {$type['code']}: " . $e->getMessage() . "\n";
        }
    }
    
    // Verify the relationships for +19
    echo "\n📊 VERIFICATION - +19 category relationships:\n";
    $verifyQuery = $connection->execute('
        SELECT ft.code, ft.name
        FROM football_categories_types fct
        JOIN football_types ft ON fct.football_type_id = ft.id
        WHERE fct.football_category_id = ?
        ORDER BY ft.code
    ', [$categoryId]);
    
    $count = 0;
    while ($row = $verifyQuery->fetch()) {
        echo "  +19 → {$row[0]} ({$row[1]})\n";
        $count++;
    }
    
    if ($count === 0) {
        echo "  ❌ No relationships found for +19!\n";
    } else {
        echo "\n✅ +19 category now has $count football type relationships!\n";
        echo "\n💡 Now +19 category can play all available football types.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>