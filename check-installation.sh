#!/bin/bash

# Quick Installation Checker
echo "🔍 FiltCMS Installation Checker"
echo "================================"
echo ""

# Get Laravel project path
read -p "Enter your Laravel project path: " LARAVEL_PATH

if [ ! -d "$LARAVEL_PATH" ]; then
    echo "❌ Error: Directory does not exist!"
    exit 1
fi

cd "$LARAVEL_PATH" || exit

echo ""
echo "Checking installation..."
echo ""

# Check if package is in composer.json
echo "1️⃣  Checking composer.json..."
if grep -q "ethicks/filtcms" composer.json; then
    echo "   ✅ Package is in composer.json"
    grep -A 1 "ethicks/filtcms" composer.json
else
    echo "   ❌ Package NOT in composer.json"
fi

echo ""
echo "2️⃣  Checking repositories..."
if grep -q "filtcms" composer.json; then
    echo "   ✅ Repository configured"
    grep -B 2 -A 4 "filtcms" composer.json | head -10
else
    echo "   ⚠️  No repository configured"
fi

echo ""
echo "3️⃣  Checking vendor directory..."
if [ -d "vendor/ethicks/filtcms" ]; then
    echo "   ✅ Package directory exists: vendor/ethicks/filtcms"
    
    if [ -L "vendor/ethicks/filtcms" ]; then
        echo "   📎 It's a SYMLINK to: $(readlink vendor/ethicks/filtcms)"
        SYMLINK_TARGET=$(readlink vendor/ethicks/filtcms)
        if [ -d "$SYMLINK_TARGET" ]; then
            echo "   ✅ Symlink target exists"
        else
            echo "   ❌ Symlink target DOES NOT exist!"
        fi
    else
        echo "   📁 It's a REAL directory (copied, not symlinked)"
    fi
    
    echo ""
    echo "   Contents:"
    ls -la vendor/ethicks/filtcms/
else
    echo "   ❌ Package directory DOES NOT exist"
fi

echo ""
echo "4️⃣  Checking plugin file..."
if [ -f "vendor/ethicks/filtcms/src/FiltCMSPlugin.php" ]; then
    echo "   ✅ FiltCMSPlugin.php exists"
    echo "   First few lines:"
    head -20 vendor/ethicks/filtcms/src/FiltCMSPlugin.php
else
    echo "   ❌ FiltCMSPlugin.php DOES NOT exist"
fi

echo ""
echo "5️⃣  Checking autoload..."
if grep -q "EthickS" vendor/composer/autoload_psr4.php; then
    echo "   ✅ Namespace registered in autoload"
    grep "EthickS" vendor/composer/autoload_psr4.php
else
    echo "   ❌ Namespace NOT registered in autoload"
fi

echo ""
echo "6️⃣  Testing class loading..."
php -r "
require 'vendor/autoload.php';
echo '   Checking class_exists()... ';
if (class_exists('EthickS\\\\FiltCMS\\\\FiltCMSPlugin')) {
    echo '✅ SUCCESS!\n';
    echo '   Class is loadable.\n';
    \$reflection = new ReflectionClass('EthickS\\\\FiltCMS\\\\FiltCMSPlugin');
    echo '   File: ' . \$reflection->getFileName() . '\n';
} else {
    echo '❌ FAILED!\n';
    echo '   Class cannot be loaded.\n';
    echo '\n';
    echo '   Registered namespaces:\n';
    \$loader = require 'vendor/autoload.php';
    \$prefixes = \$loader->getPrefixesPsr4();
    foreach (\$prefixes as \$namespace => \$paths) {
        if (strpos(\$namespace, 'EthickS') !== false || strpos(\$namespace, 'FiltCMS') !== false) {
            echo '   - ' . \$namespace . ' => ' . implode(', ', \$paths) . '\n';
        }
    }
}
"

echo ""
echo "7️⃣  Checking composer packages..."
composer show | grep filtcms || echo "   ⚠️  Package not shown in composer show"

echo ""
echo "================================"
echo "Diagnostic complete!"
echo ""
