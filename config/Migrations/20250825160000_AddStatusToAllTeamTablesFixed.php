<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddStatusToAllTeamTablesFixed extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Add status columns to all team tables
        $tables = [
            'teams',
            'basketball_teams', 
            'handball_teams',
            'volleyball_teams',
            'beachvolley_teams'
        ];
        
        foreach ($tables as $tableName) {
            // Check if columns don't exist before adding
            $this->execute("
                SET @exist := (SELECT COUNT(*) FROM information_schema.columns 
                    WHERE table_name='{$tableName}' AND column_name='status' AND table_schema=DATABASE());
                SET @sqlstmt := IF(@exist=0, 
                    'ALTER TABLE {$tableName} ADD COLUMN status ENUM(\"pending\", \"verified\", \"rejected\") NOT NULL DEFAULT \"pending\"',
                    'SELECT \"Column status already exists\"');
                PREPARE stmt FROM @sqlstmt;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
            ");
            
            $this->execute("
                SET @exist := (SELECT COUNT(*) FROM information_schema.columns 
                    WHERE table_name='{$tableName}' AND column_name='verified_at' AND table_schema=DATABASE());
                SET @sqlstmt := IF(@exist=0, 
                    'ALTER TABLE {$tableName} ADD COLUMN verified_at DATETIME NULL',
                    'SELECT \"Column verified_at already exists\"');
                PREPARE stmt FROM @sqlstmt;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
            ");
            
            $this->execute("
                SET @exist := (SELECT COUNT(*) FROM information_schema.columns 
                    WHERE table_name='{$tableName}' AND column_name='verified_by' AND table_schema=DATABASE());
                SET @sqlstmt := IF(@exist=0, 
                    'ALTER TABLE {$tableName} ADD COLUMN verified_by INT NULL',
                    'SELECT \"Column verified_by already exists\"');
                PREPARE stmt FROM @sqlstmt;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
            ");
            
            $this->execute("
                SET @exist := (SELECT COUNT(*) FROM information_schema.columns 
                    WHERE table_name='{$tableName}' AND column_name='verification_notes' AND table_schema=DATABASE());
                SET @sqlstmt := IF(@exist=0, 
                    'ALTER TABLE {$tableName} ADD COLUMN verification_notes TEXT NULL',
                    'SELECT \"Column verification_notes already exists\"');
                PREPARE stmt FROM @sqlstmt;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
            ");
        }
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        $tables = [
            'teams',
            'basketball_teams', 
            'handball_teams',
            'volleyball_teams',
            'beachvolley_teams'
        ];
        
        foreach ($tables as $tableName) {
            $this->execute("ALTER TABLE {$tableName} DROP COLUMN IF EXISTS verification_notes");
            $this->execute("ALTER TABLE {$tableName} DROP COLUMN IF EXISTS verified_by");
            $this->execute("ALTER TABLE {$tableName} DROP COLUMN IF EXISTS verified_at");
            $this->execute("ALTER TABLE {$tableName} DROP COLUMN IF EXISTS status");
        }
    }
}