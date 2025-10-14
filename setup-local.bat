@echo off
echo Setting up CPV Suggest Service (Local Development)
echo.

REM Check if PHP is installed
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: PHP is not installed or not in PATH
    echo Please install PHP 8.3 from https://windows.php.net/download
    pause
    exit /b 1
)

REM Check if Composer is installed
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Composer is not installed or not in PATH
    echo Please install Composer from https://getcomposer.org/download/
    pause
    exit /b 1
)

REM Copy environment file
if not exist .env (
    echo Copying .env.local to .env...
    copy .env.local .env
    echo.
    echo WARNING: Please edit .env and add your ANTHROPIC_API_KEY
    echo.
)

REM Create database directory
if not exist database mkdir database

REM Create SQLite database
echo Creating SQLite database...
type nul > database\database.sqlite

REM Install dependencies
echo Installing Composer dependencies...
composer install

REM Generate app key
echo Generating application key...
php artisan key:generate

REM Run migrations
echo Running migrations...
php artisan migrate --force

REM Seed database
echo Seeding database with CPV codes...
php artisan db:seed --force

echo.
echo Setup complete!
echo.
echo To start the server, run:
echo   php artisan serve
echo.
echo Then access at: http://localhost:8000
echo.
pause
