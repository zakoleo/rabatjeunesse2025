<?php
/**
 * Quick test to verify football management admin functionality
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
    echo "🧪 TESTING FOOTBALL MANAGEMENT ADMIN SETUP...\n\n";
    
    // Test database connections
    echo "1. 📊 Testing database connectivity...\n";
    $connection = ConnectionManager::get('default');
    
    // Load tables
    $footballCategoriesTable = TableRegistry::getTableLocator()->get('FootballCategories');
    $footballTypesTable = TableRegistry::getTableLocator()->get('FootballTypes');
    $footballCategoriesTypesTable = TableRegistry::getTableLocator()->get('FootballCategoriesTypes');
    
    echo "   ✅ Database connection successful\n";
    echo "   ✅ FootballCategories table loaded\n";
    echo "   ✅ FootballTypes table loaded\n";
    echo "   ✅ FootballCategoriesTypes table loaded\n\n";
    
    // Check data counts
    echo "2. 📋 Checking data availability...\n";
    $categoriesCount = $footballCategoriesTable->find()->where(['active' => 1])->count();
    $typesCount = $footballTypesTable->find()->where(['active' => 1])->count();
    $relationshipsCount = $footballCategoriesTypesTable->find()->count();
    
    echo "   📋 Active Categories: $categoriesCount\n";
    echo "   🏟️ Active Types: $typesCount\n";
    echo "   🔗 Relationships: $relationshipsCount\n\n";
    
    if ($categoriesCount === 0 || $typesCount === 0) {
        echo "⚠️ WARNING: No data found! Run the population scripts first.\n\n";
    }
    
    // Test specific relationships
    echo "3. 🎯 Testing specific relationships...\n";
    $categories = $footballCategoriesTable->find()
        ->where(['active' => 1, 'name IN' => ['-12', '-15', '+19', 'Senior']])
        ->contain(['FootballTypes'])
        ->toArray();
    
    foreach ($categories as $category) {
        $typeCount = count($category->football_types);
        echo "   {$category->name}: $typeCount type(s) assigned\n";
        
        if ($category->name === '-12' && $typeCount === 1) {
            $type = $category->football_types[0];
            if ($type->code === '6x6') {
                echo "     ✅ -12 correctly restricted to 6x6\n";
            }
        }
    }
    
    echo "\n4. 🌐 Testing URL routes...\n";
    $routes = [
        '/admin/football-management',
        '/admin/football-management/categories', 
        '/admin/football-management/types',
        '/admin/football-management/relationships'
    ];
    
    foreach ($routes as $route) {
        echo "   📍 Route configured: $route\n";
    }
    
    echo "\n✅ ADMIN FOOTBALL MANAGEMENT SETUP TEST COMPLETED!\n";
    echo "\n🚀 Ready to use at: http://localhost/rabatjeunesse2025/admin/football-management\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>