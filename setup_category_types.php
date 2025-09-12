<?php
/**
 * Setup script to check and populate football category-type relationships
 */

// Include CakePHP bootstrap
require 'vendor/autoload.php';

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

// Bootstrap CakePHP
$bootstrap = dirname(__FILE__) . '/config/bootstrap.php';
require $bootstrap;

try {
    // Get the database connection
    $connection = ConnectionManager::get('default');
    
    echo "=== CHECKING EXISTING DATA ===\n\n";
    
    // Check football categories
    echo "📋 FOOTBALL CATEGORIES:\n";
    $categoriesQuery = $connection->execute('SELECT id, name, active FROM football_categories ORDER BY id');
    $categories = $categoriesQuery->fetchAll();
    
    foreach ($categories as $category) {
        echo "  ID: {$category['id']} | Name: {$category['name']} | Active: " . ($category['active'] ? 'Yes' : 'No') . "\n";
    }
    
    echo "\n⚽ FOOTBALL TYPES:\n";
    $typesQuery = $connection->execute('SELECT id, name, code, min_players, max_players, active FROM football_types ORDER BY id');
    $types = $typesQuery->fetchAll();
    
    foreach ($types as $type) {
        echo "  ID: {$type['id']} | Name: {$type['name']} | Code: {$type['code']} | Players: {$type['min_players']}-{$type['max_players']} | Active: " . ($type['active'] ? 'Yes' : 'No') . "\n";
    }
    
    // Check existing relationships
    echo "\n🔗 EXISTING RELATIONSHIPS:\n";
    $relationQuery = $connection->execute('
        SELECT fct.id, fc.name as category_name, ft.name as type_name, ft.code
        FROM football_categories_types fct
        JOIN football_categories fc ON fct.football_category_id = fc.id  
        JOIN football_types ft ON fct.football_type_id = ft.id
        ORDER BY fc.name, ft.name
    ');
    $relations = $relationQuery->fetchAll();
    
    if (count($relations) > 0) {
        foreach ($relations as $relation) {
            echo "  {$relation['category_name']} → {$relation['type_name']} ({$relation['code']})\n";
        }
    } else {
        echo "  No relationships found!\n";
        echo "\n🚀 CREATING EXAMPLE RELATIONSHIPS:\n";
        
        // Find -12 category and 6x6 type for example
        $minus12Category = null;
        $type6x6 = null;
        
        foreach ($categories as $cat) {
            if (strpos($cat['name'], '-12') !== false || $cat['name'] == '-12') {
                $minus12Category = $cat;
                break;
            }
        }
        
        foreach ($types as $type) {
            if ($type['code'] == '6x6' || strpos($type['name'], '6x6') !== false) {
                $type6x6 = $type;
                break;
            }
        }
        
        if ($minus12Category && $type6x6) {
            echo "  Creating relationship: {$minus12Category['name']} → {$type6x6['name']}\n";
            
            $insertQuery = $connection->execute('
                INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
                VALUES (?, ?, NOW(), NOW())
            ', [$minus12Category['id'], $type6x6['id']]);
            
            echo "  ✅ Relationship created successfully!\n";
        } else {
            echo "  ❌ Could not find -12 category or 6x6 type for example\n";
            echo "  Available categories: " . implode(', ', array_column($categories, 'name')) . "\n";
            echo "  Available types: " . implode(', ', array_column($types, 'code')) . "\n";
        }
        
        echo "\n💡 TO ADD MORE RELATIONSHIPS:\n";
        echo "  Run this SQL in your database:\n";
        echo "  INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)\n";
        echo "  VALUES (category_id, type_id, NOW(), NOW());\n\n";
        
        echo "  Example relationships you might want:\n";
        foreach ($categories as $cat) {
            if ($cat['active']) {
                echo "  -- {$cat['name']} (ID: {$cat['id']}) can play:\n";
                if (strpos($cat['name'], '-12') !== false) {
                    echo "  --   Only 6x6 (smaller field for younger players)\n";
                } elseif (strpos($cat['name'], '-15') !== false) {
                    echo "  --   6x6 and 11x11 (transitional age)\n";
                } else {
                    echo "  --   All formats: 5x5, 6x6, 11x11\n";
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>