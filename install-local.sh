#!/bin/bash

# FiltCMS Local Development Setup Script
# This script helps you install FiltCMS plugin locally for testing

echo "🚀 FiltCMS Local Development Setup"
echo "=================================="
echo ""

# Get Laravel project path
read -p "Enter your Laravel project path: " LARAVEL_PATH

if [ ! -d "$LARAVEL_PATH" ]; then
    echo "❌ Error: Directory does not exist!"
    exit 1
fi

# Get plugin path (current directory)
PLUGIN_PATH="$(pwd)"

echo ""
echo "📦 Plugin Path: $PLUGIN_PATH"
echo "🏠 Laravel Path: $LARAVEL_PATH"
echo ""

# Navigate to Laravel project
cd "$LARAVEL_PATH" || exit

echo "📝 Adding path repository to composer.json..."

# Add repository configuration
composer config repositories.filtcms '{"type": "path", "url": "'"$PLUGIN_PATH"'", "options": {"symlink": true}}' --file composer.json

echo "✅ Repository added!"
echo ""

echo "📥 Installing package..."
composer require ethicks/filtcms @dev

echo ""
echo "� Regenerating autoload files..."
composer dump-autoload

echo ""
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo ""
echo "�📋 Publishing configuration and migrations..."
php artisan vendor:publish --tag="filtcms-config" --force
php artisan vendor:publish --tag="filtcms-migrations" --force

echo ""
read -p "🗄️  Run migrations now? (y/n): " RUN_MIGRATIONS

if [ "$RUN_MIGRATIONS" = "y" ] || [ "$RUN_MIGRATIONS" = "Y" ]; then
    php artisan migrate
    echo "✅ Migrations completed!"
fi

echo ""
echo "✨ Setup complete!"
echo ""
echo "Next steps:"
echo "1. Add FiltCMSPlugin::make() to your panel provider"
echo "2. Visit your Filament admin panel"
echo "3. Look for the 'FiltCMS' navigation group"
echo ""
echo "📝 Example panel provider code:"
echo ""
echo "use EthickS\FiltCMS\FiltCMSPlugin;"
echo ""
echo "public function panel(Panel \$panel): Panel"
echo "{"
echo "    return \$panel"
echo "        ->plugins(["
echo "            FiltCMSPlugin::make(),"
echo "        ]);"
echo "}"
echo ""
echo "Happy coding! 🎉"
