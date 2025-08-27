@echo off
echo ===============================================
echo    Reset and Run CakePHP Migrations
echo ===============================================
echo.

REM Change to project directory
cd /d "%~dp0"

echo 1. Dropping problematic tables manually...
echo.
mysql -u root -e "USE rabatjeunesse2025; SET FOREIGN_KEY_CHECKS=0; DROP TABLE IF EXISTS handball_teams_joueurs; DROP TABLE IF EXISTS handball_teams; DROP TABLE IF EXISTS basketball_teams_joueurs; DROP TABLE IF EXISTS basketball_teams; SET FOREIGN_KEY_CHECKS=1;"

echo.
echo 2. Rolling back migrations...
echo.
php bin\cake.php migrations rollback

echo.
echo 3. Checking migration status...
echo.
php bin\cake.php migrations status

echo.
echo 4. Running all migrations...
echo.
php bin\cake.php migrations migrate

echo.
echo 5. Final migration status...
echo.
php bin\cake.php migrations status

echo.
echo ===============================================
echo             Migration Reset Complete
echo ===============================================
echo.
echo Check above for any errors. If successful, 
echo both basketball and handball tables should 
echo now be properly created.
echo.
pause