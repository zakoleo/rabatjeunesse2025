<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddStatusToAllTeamTables extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Add status column to all team tables
        $tables = [
            'teams',
            'basketball_teams', 
            'handball_teams',
            'volleyball_teams',
            'beachvolley_teams'
        ];
        
        foreach ($tables as $tableName) {
            $this->execute("
                ALTER TABLE {$tableName} 
                ADD COLUMN IF NOT EXISTS status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending' 
                AFTER reference_inscription
            ");
            
            $this->execute("
                ALTER TABLE {$tableName} 
                ADD COLUMN IF NOT EXISTS verified_at DATETIME NULL 
                AFTER status
            ");
            
            $this->execute("
                ALTER TABLE {$tableName} 
                ADD COLUMN IF NOT EXISTS verified_by INT NULL 
                AFTER verified_at
            ");
            
            $this->execute("
                ALTER TABLE {$tableName} 
                ADD COLUMN IF NOT EXISTS verification_notes TEXT NULL 
                AFTER verified_by
            ");
        }
        
        // Add foreign key constraints for verified_by
        foreach ($tables as $tableName) {
            $this->execute("
                ALTER TABLE {$tableName} 
                ADD CONSTRAINT fk_{$tableName}_verified_by 
                FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
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
            // Drop foreign key first
            $this->execute("ALTER TABLE {$tableName} DROP FOREIGN KEY fk_{$tableName}_verified_by");
            
            // Drop columns
            $this->execute("ALTER TABLE {$tableName} DROP COLUMN verification_notes");
            $this->execute("ALTER TABLE {$tableName} DROP COLUMN verified_by");
            $this->execute("ALTER TABLE {$tableName} DROP COLUMN verified_at");
            $this->execute("ALTER TABLE {$tableName} DROP COLUMN status");
        }
    }
}