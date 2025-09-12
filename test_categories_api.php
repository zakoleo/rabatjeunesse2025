<?php
/**
 * Test script to verify football categories API functionality
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
    echo "🧪 TESTING FOOTBALL CATEGORIES API FUNCTIONALITY...\n\n";
    
    // Load the FootballCategoriesTable
    $footballCategoriesTable = TableRegistry::getTableLocator()->get('FootballCategories');
    
    // Get categories with allowed football types (same logic as in controller)
    $categories = $footballCategoriesTable->find()
        ->where(['active' => 1])
        ->contain(['FootballTypes' => function ($q) {
            return $q->where(['FootballTypes.active' => 1]);
        }])
        ->toArray();
    
    echo "📋 Found " . count($categories) . " active categories:\n\n";
    
    $result = [];
    foreach ($categories as $category) {
        echo "Category: {$category->name}\n";
        echo "  Birth dates: {$category->min_date} to {$category->max_date}\n";
        echo "  Allowed football types:\n";
        
        $allowed_types = [];
        foreach ($category->football_types as $type) {
            echo "    - {$type->name} ({$type->code}) - {$type->min_players}-{$type->max_players} players\n";
            $allowed_types[] = [
                'id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'min_players' => $type->min_players,
                'max_players' => $type->max_players
            ];
        }
        
        $result[] = [
            'id' => $category->id,
            'name' => $category->name,
            'min_date' => $category->min_date,
            'max_date' => $category->max_date,
            'allowed_football_types' => $allowed_types
        ];
        
        echo "\n";
    }
    
    // Show the JSON that would be returned by the API
    echo "🌐 JSON that would be returned by API:\n";
    echo "=====================================\n";
    echo json_encode(['categories' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "\n=====================================\n";
    
    // Test specific case: -12 category
    echo "\n🎯 SPECIFIC TEST: -12 category should only have 6x6 type:\n";
    $minus12Category = null;
    foreach ($result as $cat) {
        if ($cat['name'] === '-12') {
            $minus12Category = $cat;
            break;
        }
    }
    
    if ($minus12Category) {
        echo "✅ Found -12 category (ID: {$minus12Category['id']})\n";
        echo "  Allowed types: ";
        $typeCodes = array_column($minus12Category['allowed_football_types'], 'code');
        echo implode(', ', $typeCodes) . "\n";
        
        if (count($typeCodes) === 1 && $typeCodes[0] === '6x6') {
            echo "  ✅ PERFECT! -12 category only allows 6x6 as expected!\n";
        } else {
            echo "  ❌ ISSUE! -12 category should only allow 6x6, but allows: " . implode(', ', $typeCodes) . "\n";
        }
    } else {
        echo "❌ -12 category not found!\n";
    }
    
    echo "\n✅ API FUNCTIONALITY TEST COMPLETED!\n";
    echo "\n💡 The JavaScript filtering should now work properly when you select different categories.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>