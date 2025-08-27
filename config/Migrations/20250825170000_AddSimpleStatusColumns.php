<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddSimpleStatusColumns extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Simple approach - add columns directly
        $this->execute("ALTER TABLE teams ADD COLUMN status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending'");
        $this->execute("ALTER TABLE teams ADD COLUMN verified_at DATETIME NULL");
        $this->execute("ALTER TABLE teams ADD COLUMN verified_by INT NULL");
        $this->execute("ALTER TABLE teams ADD COLUMN verification_notes TEXT NULL");
        
        $this->execute("ALTER TABLE basketball_teams ADD COLUMN status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending'");
        $this->execute("ALTER TABLE basketball_teams ADD COLUMN verified_at DATETIME NULL");
        $this->execute("ALTER TABLE basketball_teams ADD COLUMN verified_by INT NULL");
        $this->execute("ALTER TABLE basketball_teams ADD COLUMN verification_notes TEXT NULL");
        
        $this->execute("ALTER TABLE handball_teams ADD COLUMN status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending'");
        $this->execute("ALTER TABLE handball_teams ADD COLUMN verified_at DATETIME NULL");
        $this->execute("ALTER TABLE handball_teams ADD COLUMN verified_by INT NULL");
        $this->execute("ALTER TABLE handball_teams ADD COLUMN verification_notes TEXT NULL");
        
        $this->execute("ALTER TABLE volleyball_teams ADD COLUMN status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending'");
        $this->execute("ALTER TABLE volleyball_teams ADD COLUMN verified_at DATETIME NULL");
        $this->execute("ALTER TABLE volleyball_teams ADD COLUMN verified_by INT NULL");
        $this->execute("ALTER TABLE volleyball_teams ADD COLUMN verification_notes TEXT NULL");
        
        $this->execute("ALTER TABLE beachvolley_teams ADD COLUMN status ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending'");
        $this->execute("ALTER TABLE beachvolley_teams ADD COLUMN verified_at DATETIME NULL");
        $this->execute("ALTER TABLE beachvolley_teams ADD COLUMN verified_by INT NULL");
        $this->execute("ALTER TABLE beachvolley_teams ADD COLUMN verification_notes TEXT NULL");
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        $tables = ['teams', 'basketball_teams', 'handball_teams', 'volleyball_teams', 'beachvolley_teams'];
        
        foreach ($tables as $table) {
            $this->execute("ALTER TABLE {$table} DROP COLUMN verification_notes");
            $this->execute("ALTER TABLE {$table} DROP COLUMN verified_by");
            $this->execute("ALTER TABLE {$table} DROP COLUMN verified_at");
            $this->execute("ALTER TABLE {$table} DROP COLUMN status");
        }
    }
}