<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddForeignKeyConstraints extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Add foreign key constraints to basketball_teams table
        if ($this->hasTable('basketball_teams')) {
            $basketballTable = $this->table('basketball_teams');
            
            // Only add constraints if they don't already exist
            try {
                $basketballTable->addForeignKey('user_id', 'users', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }

            try {
                $basketballTable->addForeignKey('basketball_category_id', 'football_categories', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }

            try {
                $basketballTable->addForeignKey('basketball_district_id', 'football_districts', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }

            try {
                $basketballTable->addForeignKey('basketball_organisation_id', 'football_organisations', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }
        }

        // Add foreign key constraints to handball_teams table
        if ($this->hasTable('handball_teams')) {
            $handballTable = $this->table('handball_teams');
            
            try {
                $handballTable->addForeignKey('user_id', 'users', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }

            try {
                $handballTable->addForeignKey('handball_category_id', 'football_categories', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }

            try {
                $handballTable->addForeignKey('handball_district_id', 'football_districts', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }

            try {
                $handballTable->addForeignKey('handball_organisation_id', 'football_organisations', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }
        }

        // Add foreign key constraints to basketball_teams_joueurs table
        if ($this->hasTable('basketball_teams_joueurs')) {
            $basketballJoueursTable = $this->table('basketball_teams_joueurs');
            
            try {
                $basketballJoueursTable->addForeignKey('basketball_team_id', 'basketball_teams', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }
        }

        // Add foreign key constraints to handball_teams_joueurs table
        if ($this->hasTable('handball_teams_joueurs')) {
            $handballJoueursTable = $this->table('handball_teams_joueurs');
            
            try {
                $handballJoueursTable->addForeignKey('handball_team_id', 'handball_teams', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION'
                ])->save();
            } catch (Exception $e) {
                // Foreign key already exists, skip
            }
        }
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        // Remove foreign key constraints
        if ($this->hasTable('basketball_teams')) {
            $this->execute('ALTER TABLE basketball_teams DROP FOREIGN KEY IF EXISTS basketball_teams_ibfk_1');
            $this->execute('ALTER TABLE basketball_teams DROP FOREIGN KEY IF EXISTS basketball_teams_ibfk_2');
            $this->execute('ALTER TABLE basketball_teams DROP FOREIGN KEY IF EXISTS basketball_teams_ibfk_3');
            $this->execute('ALTER TABLE basketball_teams DROP FOREIGN KEY IF EXISTS basketball_teams_ibfk_4');
        }

        if ($this->hasTable('handball_teams')) {
            $this->execute('ALTER TABLE handball_teams DROP FOREIGN KEY IF EXISTS handball_teams_ibfk_1');
            $this->execute('ALTER TABLE handball_teams DROP FOREIGN KEY IF EXISTS handball_teams_ibfk_2');
            $this->execute('ALTER TABLE handball_teams DROP FOREIGN KEY IF EXISTS handball_teams_ibfk_3');
            $this->execute('ALTER TABLE handball_teams DROP FOREIGN KEY IF EXISTS handball_teams_ibfk_4');
        }

        if ($this->hasTable('basketball_teams_joueurs')) {
            $this->execute('ALTER TABLE basketball_teams_joueurs DROP FOREIGN KEY IF EXISTS basketball_teams_joueurs_ibfk_1');
        }

        if ($this->hasTable('handball_teams_joueurs')) {
            $this->execute('ALTER TABLE handball_teams_joueurs DROP FOREIGN KEY IF EXISTS handball_teams_joueurs_ibfk_1');
        }
    }
}