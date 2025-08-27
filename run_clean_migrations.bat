@echo off
echo ===============================================
echo    Clean Migration Run for Sports Tables
echo ===============================================
echo.

REM Change to project directory
cd /d "%~dp0"

echo 1. Dropping existing sports tables manually...
echo.
mysql -u root -e "USE rabatjeunesse2025; SET FOREIGN_KEY_CHECKS=0; DROP TABLE IF EXISTS handball_teams_joueurs; DROP TABLE IF EXISTS handball_teams; DROP TABLE IF EXISTS basketball_teams_joueurs; DROP TABLE IF EXISTS basketball_teams; SET FOREIGN_KEY_CHECKS=1;" 2>nul

echo.
echo 2. Marking problematic migrations as not run...
echo.
mysql -u root -e "USE rabatjeunesse2025; DELETE FROM phinxlog WHERE migration_name LIKE '%%CreateBasketballTeams%%' OR migration_name LIKE '%%CreateHandballTeams%%' OR migration_name LIKE '%%UpdateHandballTablesStructure%%';" 2>nul

echo.
echo 3. Checking current migration status...
echo.
php bin\cake.php migrations status

echo.
echo 4. Running migrations (will skip already completed ones)...
echo.
php bin\cake.php migrations migrate

echo.
echo 5. Final migration status...
echo.
php bin\cake.php migrations status

echo.
echo 6. Verifying tables were created...
echo.
mysql -u root -e "USE rabatjeunesse2025; SHOW TABLES LIKE '%%basketball%%'; SHOW TABLES LIKE '%%handball%%';" 2>nul

echo.
echo ===============================================
echo             Clean Migration Complete
echo ===============================================
echo.
echo If successful, you should see basketball_teams, 
echo basketball_teams_joueurs, handball_teams, and 
echo handball_teams_joueurs tables listed above.
echo.
pause