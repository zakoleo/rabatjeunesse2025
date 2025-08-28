<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class UpdateAllTablesCollation extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // List all existing tables that need collation update
        $tables = [
            'users',
            'teams',
            'basketball_teams',
            'handball_teams',
            'volleyball_teams',
            'beachvolley_teams',
            'basketball_teams_joueurs',
            'handball_teams_joueurs',
            'volleyball_teams_joueurs',
            'beachvolley_teams_joueurs',
            'joueurs',
            'football_categories',
            'football_districts',
            'football_organisations'
        ];

        // Update each table to use utf8mb3_general_ci
        foreach ($tables as $table) {
            $this->execute("ALTER TABLE {$table} CONVERT TO CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci");
        }
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        // Revert to default charset/collation if needed
        $tables = [
            'users',
            'teams',
            'basketball_teams',
            'handball_teams',
            'volleyball_teams',
            'beachvolley_teams',
            'basketball_teams_joueurs',
            'handball_teams_joueurs',
            'volleyball_teams_joueurs',
            'beachvolley_teams_joueurs',
            'joueurs',
            'football_categories',
            'football_districts',
            'football_organisations'
        ];

        foreach ($tables as $table) {
            $this->execute("ALTER TABLE {$table} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }
    }
}
