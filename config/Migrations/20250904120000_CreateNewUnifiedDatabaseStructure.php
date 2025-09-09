<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateNewUnifiedDatabaseStructure extends BaseMigration
{
    /**
     * Up Method - Create new unified database structure
     */
    public function up(): void
    {
        // 1. Sports table (football, basketball, handball, volleyball, beachvolley)
        $sportsTable = $this->table('sports');
        $sportsTable
            ->addColumn('name', 'string', ['limit' => 50, 'null' => false, 'comment' => 'Sport name'])
            ->addColumn('code', 'string', ['limit' => 20, 'null' => false, 'comment' => 'Sport code (football, basketball, etc.)'])
            ->addColumn('min_players', 'integer', ['null' => false, 'comment' => 'Minimum players per team'])
            ->addColumn('max_players', 'integer', ['null' => false, 'comment' => 'Maximum players per team'])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        // 2. Football types table (5x5, 6x6, 11x11)
        $footballTypesTable = $this->table('football_types');
        $footballTypesTable
            ->addColumn('name', 'string', ['limit' => 20, 'null' => false, 'comment' => 'Football type name'])
            ->addColumn('code', 'string', ['limit' => 10, 'null' => false, 'comment' => 'Football type code (5x5, 6x6, 11x11)'])
            ->addColumn('min_players', 'integer', ['null' => false, 'comment' => 'Minimum players'])
            ->addColumn('max_players', 'integer', ['null' => false, 'comment' => 'Maximum players'])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        // 3. Districts table
        $districtsTable = $this->table('districts');
        $districtsTable
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false, 'comment' => 'District name'])
            ->addColumn('code', 'string', ['limit' => 20, 'null' => true, 'comment' => 'District code'])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->create();

        // 4. Organizations table  
        $organizationsTable = $this->table('organizations');
        $organizationsTable
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false, 'comment' => 'Organization name'])
            ->addColumn('code', 'string', ['limit' => 20, 'null' => true, 'comment' => 'Organization code'])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->create();

        // 5. Categories table (age categories per sport)
        $categoriesTable = $this->table('categories');
        $categoriesTable
            ->addColumn('sport_id', 'integer', ['null' => false, 'comment' => 'Reference to sports table'])
            ->addColumn('name', 'string', ['limit' => 50, 'null' => false, 'comment' => 'Category name (-12, -15, U17, etc.)'])
            ->addColumn('age_range', 'string', ['limit' => 100, 'null' => true, 'comment' => 'Human readable age range'])
            ->addColumn('min_birth_year', 'integer', ['null' => true, 'comment' => 'Minimum birth year'])
            ->addColumn('max_birth_year', 'integer', ['null' => true, 'comment' => 'Maximum birth year'])
            ->addColumn('min_birth_date', 'date', ['null' => true, 'comment' => 'Minimum birth date'])
            ->addColumn('max_birth_date', 'date', ['null' => true, 'comment' => 'Maximum birth date'])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('sport_id', 'sports', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['sport_id', 'name'], ['unique' => true])
            ->create();

        // 6. Role types table (manager, coach, player)
        $roleTypesTable = $this->table('role_types');
        $roleTypesTable
            ->addColumn('name', 'string', ['limit' => 50, 'null' => false, 'comment' => 'Role name'])
            ->addColumn('code', 'string', ['limit' => 20, 'null' => false, 'comment' => 'Role code (manager, coach, player)'])
            ->addColumn('active', 'boolean', ['default' => true, 'null' => false])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addIndex(['code'], ['unique' => true])
            ->create();

        // 7. People table (unified for all people: managers, coaches, players)
        $peopleTable = $this->table('people');
        $peopleTable
            ->addColumn('full_name', 'string', ['limit' => 255, 'null' => false, 'comment' => 'Full name'])
            ->addColumn('birth_date', 'date', ['null' => false, 'comment' => 'Birth date'])
            ->addColumn('phone', 'string', ['limit' => 20, 'null' => true, 'comment' => 'Phone number'])
            ->addColumn('whatsapp', 'string', ['limit' => 20, 'null' => true, 'comment' => 'WhatsApp number'])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true, 'comment' => 'Email address'])
            ->addColumn('identity_number', 'string', ['limit' => 50, 'null' => false, 'comment' => 'CIN or Passport number'])
            ->addColumn('shirt_size', 'string', ['limit' => 10, 'null' => true, 'comment' => 'Shirt size (XS, S, M, L, XL, XXL, XXXL)'])
            ->addColumn('identity_front_photo', 'string', ['limit' => 255, 'null' => true, 'comment' => 'Identity front photo path'])
            ->addColumn('identity_back_photo', 'string', ['limit' => 255, 'null' => true, 'comment' => 'Identity back photo path'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addIndex(['identity_number'], ['unique' => true])
            ->create();

        // 8. New unified teams table
        $newTeamsTable = $this->table('new_teams');
        $newTeamsTable
            ->addColumn('name', 'string', ['limit' => 255, 'null' => false, 'comment' => 'Team name'])
            ->addColumn('sport_id', 'integer', ['null' => false, 'comment' => 'Reference to sports table'])
            ->addColumn('category_id', 'integer', ['null' => false, 'comment' => 'Reference to categories table'])
            ->addColumn('football_type_id', 'integer', ['null' => true, 'comment' => 'Football type (for football teams only)'])
            ->addColumn('district_id', 'integer', ['null' => false, 'comment' => 'Reference to districts table'])
            ->addColumn('organization_id', 'integer', ['null' => false, 'comment' => 'Reference to organizations table'])
            ->addColumn('address', 'text', ['null' => false, 'comment' => 'Team address'])
            ->addColumn('user_id', 'integer', ['null' => false, 'comment' => 'Reference to users table'])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'pending', 'null' => false, 'comment' => 'Team status (pending, approved, rejected)'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('sport_id', 'sports', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('football_type_id', 'football_types', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE'])
            ->addForeignKey('district_id', 'districts', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('organization_id', 'organizations', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        // 9. Team members table (relationships between teams and people)
        $teamMembersTable = $this->table('team_members');
        $teamMembersTable
            ->addColumn('team_id', 'integer', ['null' => false, 'comment' => 'Reference to new_teams table'])
            ->addColumn('person_id', 'integer', ['null' => false, 'comment' => 'Reference to people table'])
            ->addColumn('role_type_id', 'integer', ['null' => false, 'comment' => 'Reference to role_types table'])
            ->addColumn('is_active', 'boolean', ['default' => true, 'null' => false, 'comment' => 'Is member active'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'null' => false])
            ->addForeignKey('team_id', 'new_teams', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('person_id', 'people', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('role_type_id', 'role_types', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['team_id', 'person_id', 'role_type_id'], ['unique' => true])
            ->create();
    }

    /**
     * Down Method - Drop new tables
     */
    public function down(): void
    {
        $this->table('team_members')->drop()->save();
        $this->table('new_teams')->drop()->save();
        $this->table('people')->drop()->save();
        $this->table('role_types')->drop()->save();
        $this->table('categories')->drop()->save();
        $this->table('organizations')->drop()->save();
        $this->table('districts')->drop()->save();
        $this->table('football_types')->drop()->save();
        $this->table('sports')->drop()->save();
    }
}