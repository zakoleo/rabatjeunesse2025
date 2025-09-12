<?php
/**
 * Script to populate sports types and create category-type relationships
 * Run this script to set up complete relationship data for all sports
 */

require_once 'config/bootstrap.php';

use Cake\Datasource\ConnectionManager;

$connection = ConnectionManager::get('default');

echo "🏃‍♂️ Starting sports data population...\n\n";

try {
    // Start transaction
    $connection->begin();

    // 1. BASKETBALL - Insert types and relationships
    echo "🏀 Setting up Basketball data...\n";
    
    // Basketball types
    $basketballTypes = [
        ['name' => 'Basketball 3x3', 'code' => '3x3', 'min_players' => 3, 'max_players' => 6],
        ['name' => 'Basketball 5x5', 'code' => '5x5', 'min_players' => 5, 'max_players' => 12],
    ];

    foreach ($basketballTypes as $type) {
        $connection->execute(
            "INSERT INTO basketball_types (name, code, min_players, max_players, active, created, modified) 
             VALUES (?, ?, ?, ?, 1, NOW(), NOW())",
            [$type['name'], $type['code'], $type['min_players'], $type['max_players']]
        );
    }

    // Basketball category-type relationships
    // Get category IDs
    $basketballCategories = $connection->execute("SELECT id FROM basketball_categories ORDER BY id")->fetchAll();
    $basketballTypeIds = $connection->execute("SELECT id FROM basketball_types ORDER BY id")->fetchAll();

    $basketballRelationships = [
        // -15 (id=1): 3x3 only
        [1, 1], // -15 with 3x3
        // -17 (id=2): Both 3x3 and 5x5
        [2, 1], // -17 with 3x3  
        [2, 2], // -17 with 5x5
        // -21 (id=3): 5x5 only
        [3, 2], // -21 with 5x5
        // +21 (id=4): 5x5 only
        [4, 2], // +21 with 5x5
    ];

    foreach ($basketballRelationships as $rel) {
        $connection->execute(
            "INSERT INTO basketball_categories_types (basketball_category_id, basketball_type_id, created, modified) 
             VALUES (?, ?, NOW(), NOW())",
            [$rel[0], $rel[1]]
        );
    }

    echo "   ✅ Basketball types and relationships created\n\n";

    // 2. HANDBALL - Insert types and relationships
    echo "🤾‍♂️ Setting up Handball data...\n";
    
    // Handball types
    $handballTypes = [
        ['name' => 'Handball 5x5', 'code' => '5x5', 'min_players' => 5, 'max_players' => 10],
        ['name' => 'Handball 7x7', 'code' => '7x7', 'min_players' => 7, 'max_players' => 14],
    ];

    foreach ($handballTypes as $type) {
        $connection->execute(
            "INSERT INTO handball_types (name, code, min_players, max_players, active, created, modified) 
             VALUES (?, ?, ?, ?, 1, NOW(), NOW())",
            [$type['name'], $type['code'], $type['min_players'], $type['max_players']]
        );
    }

    // Handball category-type relationships
    $handballCategories = $connection->execute("SELECT id FROM handball_categories ORDER BY id")->fetchAll();
    $handballTypeIds = $connection->execute("SELECT id FROM handball_types ORDER BY id")->fetchAll();

    $handballRelationships = [
        // -15 (id=1): 5x5 only
        [1, 1], // -15 with 5x5
        // -17 (id=2): Both 5x5 and 7x7
        [2, 1], // -17 with 5x5
        [2, 2], // -17 with 7x7
        // -19 (id=3): 7x7 only
        [3, 2], // -19 with 7x7
    ];

    foreach ($handballRelationships as $rel) {
        $connection->execute(
            "INSERT INTO handball_categories_types (handball_category_id, handball_type_id, created, modified) 
             VALUES (?, ?, NOW(), NOW())",
            [$rel[0], $rel[1]]
        );
    }

    echo "   ✅ Handball types and relationships created\n\n";

    // 3. VOLLEYBALL - Insert types and relationships
    echo "🏐 Setting up Volleyball data...\n";
    
    // Volleyball types
    $volleyballTypes = [
        ['name' => 'Volleyball 4x4', 'code' => '4x4', 'min_players' => 4, 'max_players' => 8],
        ['name' => 'Volleyball 6x6', 'code' => '6x6', 'min_players' => 6, 'max_players' => 12],
    ];

    foreach ($volleyballTypes as $type) {
        $connection->execute(
            "INSERT INTO volleyball_types (name, code, min_players, max_players, active, created, modified) 
             VALUES (?, ?, ?, ?, 1, NOW(), NOW())",
            [$type['name'], $type['code'], $type['min_players'], $type['max_players']]
        );
    }

    // Volleyball category-type relationships
    $volleyballCategories = $connection->execute("SELECT id FROM volleyball_categories ORDER BY id")->fetchAll();
    $volleyballTypeIds = $connection->execute("SELECT id FROM volleyball_types ORDER BY id")->fetchAll();

    $volleyballRelationships = [
        // -15 (id=1): 4x4 only
        [1, 1], // -15 with 4x4
        // -17 (id=2): Both 4x4 and 6x6
        [2, 1], // -17 with 4x4
        [2, 2], // -17 with 6x6
        // -19 (id=3): 6x6 only
        [3, 2], // -19 with 6x6
    ];

    foreach ($volleyballRelationships as $rel) {
        $connection->execute(
            "INSERT INTO volleyball_categories_types (volleyball_category_id, volleyball_type_id, created, modified) 
             VALUES (?, ?, NOW(), NOW())",
            [$rel[0], $rel[1]]
        );
    }

    echo "   ✅ Volleyball types and relationships created\n\n";

    // 4. BEACH VOLLEYBALL - Insert types and relationships
    echo "🏖️ Setting up Beach Volleyball data...\n";
    
    // Beach Volleyball types (typically only 2x2)
    $beachvolleyTypes = [
        ['name' => 'Beach Volley 2x2', 'code' => '2x2', 'min_players' => 2, 'max_players' => 4],
        ['name' => 'Beach Volley 4x4', 'code' => '4x4', 'min_players' => 4, 'max_players' => 8],
    ];

    foreach ($beachvolleyTypes as $type) {
        $connection->execute(
            "INSERT INTO beachvolley_types (name, code, min_players, max_players, active, created, modified) 
             VALUES (?, ?, ?, ?, 1, NOW(), NOW())",
            [$type['name'], $type['code'], $type['min_players'], $type['max_players']]
        );
    }

    // Beach Volleyball category-type relationships
    $beachvolleyCategories = $connection->execute("SELECT id FROM beachvolley_categories ORDER BY id")->fetchAll();
    $beachvolleyTypeIds = $connection->execute("SELECT id FROM beachvolley_types ORDER BY id")->fetchAll();

    $beachvolleyRelationships = [
        // -17 (id=1): 2x2 only
        [1, 1], // -17 with 2x2
        // -21 (id=2): Both 2x2 and 4x4
        [2, 1], // -21 with 2x2
        [2, 2], // -21 with 4x4
        // +21 (id=3): Both 2x2 and 4x4
        [3, 1], // +21 with 2x2
        [3, 2], // +21 with 4x4
    ];

    foreach ($beachvolleyRelationships as $rel) {
        $connection->execute(
            "INSERT INTO beachvolley_categories_types (beachvolley_category_id, beachvolley_type_id, created, modified) 
             VALUES (?, ?, NOW(), NOW())",
            [$rel[0], $rel[1]]
        );
    }

    echo "   ✅ Beach Volleyball types and relationships created\n\n";

    // Commit transaction
    $connection->commit();

    echo "🎉 SUCCESS! All sports relationships have been populated successfully!\n\n";
    
    echo "📊 SUMMARY:\n";
    echo "   🏀 Basketball: 2 types, 5 relationships\n";
    echo "   🤾‍♂️ Handball: 2 types, 4 relationships\n";
    echo "   🏐 Volleyball: 2 types, 4 relationships\n";
    echo "   🏖️ Beach Volleyball: 2 types, 5 relationships\n";
    echo "   ⚽ Football: Already had 3 types, 12 relationships\n\n";

    // Display final counts
    $counts = [
        'Basketball relationships' => $connection->execute("SELECT COUNT(*) as c FROM basketball_categories_types")->fetch()['c'],
        'Handball relationships' => $connection->execute("SELECT COUNT(*) as c FROM handball_categories_types")->fetch()['c'],
        'Volleyball relationships' => $connection->execute("SELECT COUNT(*) as c FROM volleyball_categories_types")->fetch()['c'],
        'Beach Volleyball relationships' => $connection->execute("SELECT COUNT(*) as c FROM beachvolley_categories_types")->fetch()['c'],
        'Football relationships' => $connection->execute("SELECT COUNT(*) as c FROM football_categories_types")->fetch()['c'],
    ];

    echo "📈 FINAL DATABASE COUNTS:\n";
    foreach ($counts as $sport => $count) {
        echo "   $sport: $count\n";
    }

} catch (Exception $e) {
    // Rollback on error
    $connection->rollback();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Transaction rolled back.\n";
    exit(1);
}

echo "\n✨ Script completed successfully!\n";
?>