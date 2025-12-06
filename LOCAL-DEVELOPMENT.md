# Local Development Guide

## Installing FiltCMS Plugin Locally (Without Packagist)

When developing the plugin, you can test it in a Laravel project without publishing to Packagist.

## Method 1: Automated Setup (Recommended)

### For macOS/Linux:
```bash
cd /Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS
./install-local.sh
```

### For Windows:
```bash
cd C:\path\to\filtCMS
install-local.bat
```

The script will:
1. Ask for your Laravel project path
2. Configure Composer path repository
3. Install the plugin via symlink
4. Publish config and migrations
5. Optionally run migrations

---

## Method 2: Manual Setup

### Step 1: Add Path Repository

In your Laravel project's `composer.json`, add:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS",
            "options": {
                "symlink": true
            }
        }
    ]
}
```

**Windows users:** Use forward slashes or double backslashes:
```json
"url": "C:/Projects/filtCMS"
// or
"url": "C:\\Projects\\filtCMS"
```

### Step 2: Install Package

```bash
cd /path/to/your/laravel-project
composer require ethicks/filtcms @dev
```

### Step 3: Publish Assets

```bash
php artisan vendor:publish --tag="filtcms-config"
php artisan vendor:publish --tag="filtcms-migrations"
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Register Plugin

Edit `app/Providers/Filament/AdminPanelProvider.php`:

```php
use EthickS\FiltCMS\FiltCMSPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configuration
        ->plugins([
            FiltCMSPlugin::make(),
        ]);
}
```

---

## Method 3: Quick Command Line Setup

```bash
# Navigate to your Laravel project
cd /path/to/your/laravel-project

# Add repository
composer config repositories.filtcms '{"type": "path", "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS", "options": {"symlink": true}}' --file composer.json

# Install package
composer require ethicks/filtcms @dev

# Publish and migrate
php artisan vendor:publish --tag="filtcms-config"
php artisan vendor:publish --tag="filtcms-migrations"
php artisan migrate
```

---

## Benefits of Symlink Method

✅ **Live Updates** - Changes in plugin code are immediately reflected  
✅ **No Reinstall** - No need to update/reinstall after changes  
✅ **Easy Testing** - Test features as you develop  
✅ **Version Control** - Keep plugin and test project separate  

---

## Testing Your Changes

1. Make changes to plugin files in: `/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS/src/`

2. Changes are **instantly available** in your Laravel project (no rebuild needed)

3. Clear cache if needed:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## Troubleshooting

### Issue: Symlink not working
**Solution:** Remove symlink option from composer.json:
```json
{
    "type": "path",
    "url": "/path/to/filtCMS"
    // Remove "options" section
}
```
Then run: `composer update ethicks/filtcms`

### Issue: Class not found
**Solution:** 
```bash
composer dump-autoload
php artisan optimize:clear
```

### Issue: Changes not reflecting
**Solution:**
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload
```

### Issue: Migration errors
**Solution:**
```bash
# Fresh migration
php artisan migrate:fresh

# Or rollback specific
php artisan migrate:rollback
php artisan migrate
```

---

## Removing the Local Plugin

```bash
cd /path/to/your/laravel-project
composer remove ethicks/filtcms
composer config --unset repositories.filtcms
```

---

## Development Workflow

1. **Make changes** to plugin in: `/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS/`
2. **Test immediately** in your Laravel project
3. **No reinstall needed** - changes are live via symlink
4. **Commit changes** to git when satisfied
5. **Publish to Packagist** when ready for production

---

## Multiple Test Projects

You can use the same plugin in multiple Laravel projects:

```bash
# Project 1
cd /path/to/project1
composer config repositories.filtcms '{"type": "path", "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS", "options": {"symlink": true}}'
composer require ethicks/filtcms @dev

# Project 2
cd /path/to/project2
composer config repositories.filtcms '{"type": "path", "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS", "options": {"symlink": true}}'
composer require ethicks/filtcms @dev
```

All projects will use the same source code via symlink!

---

## Ready to Publish?

When ready to publish to Packagist:

1. Create GitHub repository
2. Tag a release: `git tag v1.0.0`
3. Push tag: `git push origin v1.0.0`
4. Submit to Packagist.org
5. Others can install via: `composer require ethicks/filtcms`

---

**Happy Local Development!** 🚀
