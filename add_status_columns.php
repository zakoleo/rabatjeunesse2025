<?php
// Simple script to add status columns
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'rabatjeunesse';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Adding status columns to team tables...\n";
    
    $tables = ['teams', 'basketball_teams', 'handball_teams', 'volleyball_teams', 'beachvolley_teams'];
    
    foreach ($tables as $table) {
        echo "Processing table: $table\n";
        
        // Check if columns already exist
        $checkQuery = "SHOW COLUMNS FROM $table LIKE 'status'";
        $stmt = $pdo->query($checkQuery);
        
        if ($stmt->rowCount() == 0) {
            // Add status columns
            $queries = [
                "ALTER TABLE $table ADD COLUMN status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending'",
                "ALTER TABLE $table ADD COLUMN verified_at DATETIME NULL",
                "ALTER TABLE $table ADD COLUMN verified_by INT NULL",
                "ALTER TABLE $table ADD COLUMN verification_notes TEXT NULL"
            ];
            
            foreach ($queries as $query) {
                try {
                    $pdo->exec($query);
                    echo "  ✓ Added column successfully\n";
                } catch (PDOException $e) {
                    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                        echo "  - Column already exists\n";
                    } else {
                        echo "  ✗ Error: " . $e->getMessage() . "\n";
                    }
                }
            }
        } else {
            echo "  - Status columns already exist\n";
        }
    }
    
    echo "\n✅ Status columns added successfully!\n";
    echo "\nColumns added:\n";
    echo "- status: ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'\n";
    echo "- verified_at: DATETIME NULL\n";
    echo "- verified_by: INT NULL\n"; 
    echo "- verification_notes: TEXT NULL\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?>