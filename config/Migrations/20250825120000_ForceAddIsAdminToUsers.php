<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class ForceAddIsAdminToUsers extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Check if column exists before adding it
        $this->execute("
            SELECT * FROM information_schema.columns 
            WHERE table_name='users' AND column_name='is_admin' AND table_schema='rabatjeunesse2025'
        ");
        
        // Force add the column if it doesn't exist
        $this->execute("
            ALTER TABLE users 
            ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) NOT NULL DEFAULT 0 
            AFTER email
        ");
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        $this->execute("ALTER TABLE users DROP COLUMN IF EXISTS is_admin");
    }
}