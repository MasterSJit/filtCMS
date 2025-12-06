#!/bin/bash

echo "Quick Fix: Forcing package update..."

# Get Laravel path
if [ -z "$1" ]; then
    read -p "Enter Laravel project path: " LARAVEL_PATH
else
    LARAVEL_PATH="$1"
fi

cd "$LARAVEL_PATH" || exit

echo "1. Removing vendor cache..."
rm -rf vendor/ethicks/filtcms
rm -rf bootstrap/cache/*.php

echo "2. Re-installing package..."
composer install --no-cache

echo "3. Clearing Laravel caches..."
php artisan optimize:clear 2>/dev/null || true

echo "Done! Try again."
