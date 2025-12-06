# Troubleshooting FiltCMS Installation

## Error: Class "EthickS\FiltCMS\FiltCMSPlugin" not found

This error occurs when Composer can't find the plugin classes. Here are the solutions:

### Quick Fix (Run in your Laravel project):

```bash
# Copy the fix script to your Laravel project
cp /Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS/fix-autoload.sh .

# Run it
./fix-autoload.sh
```

### Or manually:

```bash
cd /path/to/your/laravel-project

# Step 1: Regenerate autoload
composer dump-autoload

# Step 2: Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

---

## Common Issues & Solutions

### 1. Package not linked correctly

**Check if package exists:**
```bash
ls -la vendor/ethicks/filtcms
```

**If not found, reinstall:**
```bash
composer remove ethicks/filtcms
composer require ethicks/filtcms @dev
```

---

### 2. Symlink not working

**Option A: Remove symlink option**

Edit `composer.json` and change:
```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS"
            // Remove "options": {"symlink": true}
        }
    ]
}
```

Then:
```bash
composer remove ethicks/filtcms
composer require ethicks/filtcms @dev
composer dump-autoload
```

**Option B: Force copy instead of symlink**

```bash
composer config repositories.filtcms '{"type": "path", "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS"}' --file composer.json
composer update ethicks/filtcms
```

---

### 3. Wrong plugin path

**Verify the path in composer.json:**
```bash
cat composer.json | grep filtcms -A 3
```

Should show:
```json
"filtcms": {
    "type": "path",
    "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS",
```

**If path is wrong, fix it:**
```bash
composer config repositories.filtcms '{"type": "path", "url": "/correct/path/to/filtCMS", "options": {"symlink": true}}'
composer update ethicks/filtcms
```

---

### 4. Namespace issues

**Check if the plugin has the correct namespace:**
```bash
head -n 5 vendor/ethicks/filtcms/src/FiltCMSPlugin.php
```

Should show:
```php
<?php

namespace EthickS\FiltCMS;
```

---

### 5. Composer autoload not updated

```bash
composer dump-autoload -o
php artisan optimize:clear
```

---

### 6. Provider not registered

**Check if provider is loaded:**
```bash
php artisan about
```

Look for `FiltCMSServiceProvider` in the output.

**If not found, check composer.json extra section:**
```bash
cat vendor/ethicks/filtcms/composer.json | grep -A 10 '"extra"'
```

Should have:
```json
"extra": {
    "laravel": {
        "providers": [
            "EthickS\\FiltCMS\\FiltCMSServiceProvider"
        ]
    }
}
```

---

### 7. Fresh reinstall (Nuclear option)

```bash
# In your Laravel project
composer remove ethicks/filtcms
composer config --unset repositories.filtcms
rm -rf vendor/ethicks

# Re-add repository
composer config repositories.filtcms '{"type": "path", "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS", "options": {"symlink": true}}'

# Reinstall
composer require ethicks/filtcms @dev

# Clear everything
composer dump-autoload
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Verification Steps

After fixing, verify with these commands:

```bash
# 1. Check if package is installed
composer show ethicks/filtcms

# 2. Check if class can be found
php artisan tinker
>>> class_exists(\EthickS\FiltCMS\FiltCMSPlugin::class);
>>> exit

# 3. Check provider
php artisan about | grep FiltCMS

# 4. List routes
php artisan route:list | grep filtcms
```

---

## Still Not Working?

### Check AdminPanelProvider.php syntax:

```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use EthickS\FiltCMS\FiltCMSPlugin;  // ← Add this import

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            // ... other config
            ->plugins([
                FiltCMSPlugin::make(),  // ← Add this
            ]);
    }
}
```

### Check file permissions:

```bash
# Laravel project
ls -la vendor/ethicks/

# Plugin directory
ls -la /Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS/src/
```

### Check PHP version:

```bash
php -v
# Should be PHP 8.1 or higher
```

### Check Composer version:

```bash
composer --version
# Should be Composer 2.x
```

---

## Debug Mode

Add to your Laravel `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Then check logs:
```bash
tail -f storage/logs/laravel.log
```

---

## Get Help

If still having issues, run this command and share the output:

```bash
cd /path/to/your/laravel-project

echo "=== System Info ==="
php -v
composer --version

echo ""
echo "=== Composer Repositories ==="
cat composer.json | grep -A 10 repositories

echo ""
echo "=== Installed Package ==="
composer show ethicks/filtcms

echo ""
echo "=== Package Location ==="
ls -la vendor/ethicks/filtcms

echo ""
echo "=== Plugin File ==="
ls -la vendor/ethicks/filtcms/src/FiltCMSPlugin.php

echo ""
echo "=== Autoload ==="
composer dump-autoload -v
```

Save output to a file:
```bash
bash debug-script.sh > debug-info.txt 2>&1
```
