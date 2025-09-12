<?php
/**
 * Script to populate football categories, types, and relationships
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
    echo "🚀 POPULATING FOOTBALL DATA...\n\n";
    
    // Get the database connection
    $connection = ConnectionManager::get('default');
    
    // Clear existing data (optional - uncomment if you want to start fresh)
    // echo "🧹 Clearing existing data...\n";
    // $connection->execute('DELETE FROM football_categories_types');
    // $connection->execute('DELETE FROM football_categories');
    // $connection->execute('DELETE FROM football_types');
    
    // 1. Insert Football Categories
    echo "📋 Adding Football Categories...\n";
    $categoriesData = [
        ['-12', '2014-01-01', '2015-12-31', 2014, 2015],
        ['-15', '2011-01-01', '2013-12-31', 2011, 2013],
        ['-18', '2008-01-01', '2010-12-31', 2008, 2010],
        ['Senior', '1989-01-01', '2007-12-31', 1989, 2007]
    ];
    
    foreach ($categoriesData as $cat) {
        $connection->execute(
            'INSERT INTO football_categories (name, min_date, max_date, min_birth_year, max_birth_year, active, created, modified) 
             VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())
             ON DUPLICATE KEY UPDATE min_date = ?, max_date = ?, min_birth_year = ?, max_birth_year = ?, modified = NOW()',
            [$cat[0], $cat[1], $cat[2], $cat[3], $cat[4], $cat[1], $cat[2], $cat[3], $cat[4]]
        );
        echo "  ✅ Added category: {$cat[0]} (birth dates: {$cat[1]} to {$cat[2]})\n";
    }
    
    // 2. Insert Football Types
    echo "\n⚽ Adding Football Types...\n";
    $typesData = [
        ['Football 5x5', '5x5', 5, 5],
        ['Football 6x6', '6x6', 6, 6],
        ['Football 11x11', '11x11', 11, 11]
    ];
    
    foreach ($typesData as $type) {
        $connection->execute(
            'INSERT INTO football_types (name, code, min_players, max_players, active, created, modified) 
             VALUES (?, ?, ?, ?, 1, NOW(), NOW())
             ON DUPLICATE KEY UPDATE modified = NOW()',
            [$type[0], $type[1], $type[2], $type[3]]
        );
        echo "  ✅ Added type: {$type[0]} ({$type[1]}) - {$type[2]}-{$type[3]} players\n";
    }
    
    // 3. Create Category-Type Relationships
    echo "\n🔗 Creating Category-Type Relationships...\n";
    
    // Get IDs for relationships
    $categories = [];
    $categoriesQuery = $connection->execute('SELECT id, name FROM football_categories');
    foreach ($categoriesQuery->fetchAll() as $cat) {
        $categories[$cat['name']] = $cat['id'];
    }
    
    $types = [];
    $typesQuery = $connection->execute('SELECT id, code FROM football_types');
    foreach ($typesQuery->fetchAll() as $type) {
        $types[$type['code']] = $type['id'];
    }
    
    // Define relationships: [category_name => [allowed_type_codes]]
    $relationships = [
        '-12' => ['6x6'],                    // -12 can only play 6x6 (smaller field)
        '-15' => ['6x6', '11x11'],          // -15 can play 6x6 and 11x11 (transitional)
        '-18' => ['5x5', '6x6', '11x11'],   // -18 can play all formats
        'Senior' => ['5x5', '6x6', '11x11'] // Senior can play all formats
    ];
    
    foreach ($relationships as $categoryName => $allowedTypes) {
        if (!isset($categories[$categoryName])) {
            echo "  ❌ Category '$categoryName' not found!\n";
            continue;
        }
        
        foreach ($allowedTypes as $typeCode) {
            if (!isset($types[$typeCode])) {
                echo "  ❌ Type '$typeCode' not found!\n";
                continue;
            }
            
            // Insert relationship (ignore if already exists)
            try {
                $connection->execute(
                    'INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified) 
                     VALUES (?, ?, NOW(), NOW())',
                    [$categories[$categoryName], $types[$typeCode]]
                );
                echo "  ✅ $categoryName → $typeCode\n";
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    echo "  ℹ️  $categoryName → $typeCode (already exists)\n";
                } else {
                    throw $e;
                }
            }
        }
    }
    
    // 4. Verify the data
    echo "\n📊 VERIFICATION:\n";
    echo "\n📋 Categories:\n";
    $categoriesQuery = $connection->execute('SELECT id, name, min_date, max_date, active FROM football_categories ORDER BY id');
    foreach ($categoriesQuery->fetchAll() as $cat) {
        echo "  ID: {$cat['id']} | Name: {$cat['name']} | Dates: {$cat['min_date']} to {$cat['max_date']} | Active: " . ($cat['active'] ? 'Yes' : 'No') . "\n";
    }
    
    echo "\n⚽ Types:\n";
    $typesQuery = $connection->execute('SELECT id, name, code, min_players, max_players, active FROM football_types ORDER BY id');
    foreach ($typesQuery->fetchAll() as $type) {
        echo "  ID: {$type['id']} | Name: {$type['name']} | Code: {$type['code']} | Players: {$type['min_players']}-{$type['max_players']} | Active: " . ($type['active'] ? 'Yes' : 'No') . "\n";
    }
    
    echo "\n🔗 Relationships:\n";
    $relationQuery = $connection->execute('
        SELECT fc.name as category_name, ft.name as type_name, ft.code, fc.min_date, fc.max_date
        FROM football_categories_types fct
        JOIN football_categories fc ON fct.football_category_id = fc.id  
        JOIN football_types ft ON fct.football_type_id = ft.id
        ORDER BY fc.name, ft.name
    ');
    foreach ($relationQuery->fetchAll() as $relation) {
        echo "  {$relation['category_name']} (birth: {$relation['min_date']} to {$relation['max_date']}) → {$relation['type_name']} ({$relation['code']})\n";
    }
    
    echo "\n✅ FOOTBALL DATA POPULATION COMPLETED!\n";
    echo "\n💡 Now test your form - when you select '-12', you should only see '6x6' type available.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>