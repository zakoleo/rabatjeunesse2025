<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class FixBasketballTeamsStructure extends BaseMigration
{
    /**
     * Up Method.
     *
     * @return void
     */
    public function up(): void
    {
        // Drop existing basketball tables completely
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->execute('DROP TABLE IF EXISTS basketball_teams_joueurs;');
        $this->execute('DROP TABLE IF EXISTS basketball_teams;');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        // Create basketball_teams table with correct structure
        $this->execute("
            CREATE TABLE basketball_teams (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom_equipe VARCHAR(255) NOT NULL,
                categorie VARCHAR(50) NOT NULL,
                genre ENUM('Homme', 'Femme') NOT NULL,
                type_basketball ENUM('3x3', '5x5') NOT NULL,
                district VARCHAR(100) NOT NULL,
                organisation VARCHAR(100) NOT NULL,
                adresse TEXT NOT NULL,
                user_id INT NOT NULL,
                reference_inscription VARCHAR(50) DEFAULT NULL,
                
                basketball_category_id INT DEFAULT NULL,
                basketball_district_id INT DEFAULT NULL,
                basketball_organisation_id INT DEFAULT NULL,
                
                responsable_nom_complet VARCHAR(255) DEFAULT NULL,
                responsable_date_naissance DATE DEFAULT NULL,
                responsable_tel VARCHAR(20) DEFAULT NULL,
                responsable_whatsapp VARCHAR(20) DEFAULT NULL,
                responsable_cin_recto VARCHAR(255) DEFAULT NULL,
                responsable_cin_verso VARCHAR(255) DEFAULT NULL,
                
                entraineur_nom_complet VARCHAR(255) DEFAULT NULL,
                entraineur_date_naissance DATE DEFAULT NULL,
                entraineur_tel VARCHAR(20) DEFAULT NULL,
                entraineur_whatsapp VARCHAR(20) DEFAULT NULL,
                entraineur_cin_recto VARCHAR(255) DEFAULT NULL,
                entraineur_cin_verso VARCHAR(255) DEFAULT NULL,
                entraineur_same_as_responsable TINYINT(1) DEFAULT 0,
                
                accepter_reglement TINYINT(1) DEFAULT 0,
                created DATETIME DEFAULT CURRENT_TIMESTAMP,
                modified DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                UNIQUE KEY unique_reference (reference_inscription),
                INDEX idx_user_id (user_id),
                INDEX idx_category (basketball_category_id),
                INDEX idx_district (basketball_district_id),
                INDEX idx_organisation (basketball_organisation_id),
                
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (basketball_category_id) REFERENCES football_categories(id) ON DELETE SET NULL,
                FOREIGN KEY (basketball_district_id) REFERENCES football_districts(id) ON DELETE SET NULL,
                FOREIGN KEY (basketball_organisation_id) REFERENCES football_organisations(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");

        // Create basketball_teams_joueurs table
        $this->execute("
            CREATE TABLE basketball_teams_joueurs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom_complet VARCHAR(255) NOT NULL,
                date_naissance DATE NOT NULL,
                identifiant VARCHAR(50) NOT NULL,
                taille_vestimentaire ENUM('XS', 'S', 'M', 'L', 'XL', 'XXL') NOT NULL,
                basketball_team_id INT NOT NULL,
                created DATETIME DEFAULT CURRENT_TIMESTAMP,
                modified DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                INDEX idx_team_id (basketball_team_id),
                FOREIGN KEY (basketball_team_id) REFERENCES basketball_teams(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    /**
     * Down Method.
     *
     * @return void
     */
    public function down(): void
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->execute('DROP TABLE IF EXISTS basketball_teams_joueurs;');
        $this->execute('DROP TABLE IF EXISTS basketball_teams;');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}