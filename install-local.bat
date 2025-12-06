@echo off
REM FiltCMS Local Development Setup Script for Windows
REM This script helps you install FiltCMS plugin locally for testing

echo ================================
echo FiltCMS Local Development Setup
echo ================================
echo.

REM Get Laravel project path
set /p LARAVEL_PATH="Enter your Laravel project path: "

if not exist "%LARAVEL_PATH%" (
    echo Error: Directory does not exist!
    exit /b 1
)

REM Get plugin path (current directory)
set PLUGIN_PATH=%cd%

echo.
echo Plugin Path: %PLUGIN_PATH%
echo Laravel Path: %LARAVEL_PATH%
echo.

REM Navigate to Laravel project
cd /d "%LARAVEL_PATH%"

echo Adding path repository to composer.json...

REM Add repository configuration
composer config repositories.filtcms "{\"type\": \"path\", \"url\": \"%PLUGIN_PATH:\=/%\", \"options\": {\"symlink\": true}}" --file composer.json

echo Repository added!
echo.

echo Installing package...
composer require ethicks/filtcms @dev

echo.
echo Publishing configuration and migrations...
php artisan vendor:publish --tag="filtcms-config" --force
php artisan vendor:publish --tag="filtcms-migrations" --force

echo.
set /p RUN_MIGRATIONS="Run migrations now? (y/n): "

if /i "%RUN_MIGRATIONS%"=="y" (
    php artisan migrate
    echo Migrations completed!
)

echo.
echo ================================
echo Setup complete!
echo ================================
echo.
echo Next steps:
echo 1. Add FiltCMSPlugin::make() to your panel provider
echo 2. Visit your Filament admin panel
echo 3. Look for the 'FiltCMS' navigation group
echo.
echo Example panel provider code:
echo.
echo use EthickS\FiltCMS\FiltCMSPlugin;
echo.
echo public function panel(Panel $panel): Panel
echo {
echo     return $panel
echo         -^>plugins([
echo             FiltCMSPlugin::make(),
echo         ]);
echo }
echo.
echo Happy coding!
pause
