# FiltCMS - Quick Start Guide

## Installation

### For Published Package (from Packagist)

#### 1. Install the Package
```bash
composer require ethicks/filtcms
```

### For Local Development (Without Packagist)

#### Option A: Use Automated Script
```bash
# Run the setup script
cd /Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS
./install-local.sh

# Follow the prompts
```

#### Option B: Manual Setup
```bash
# In your Laravel project, add path repository
cd /path/to/your/laravel-project
composer config repositories.filtcms '{"type": "path", "url": "/Users/mastersjit/Documents/0ServerWorks/Projects/FilamentPlugin/filtCMS", "options": {"symlink": true}}' --file composer.json

# Install as local package
composer require ethicks/filtcms @dev
```

See [LOCAL-DEVELOPMENT.md](LOCAL-DEVELOPMENT.md) for detailed local setup instructions.

---

## Configuration (Both Methods)

### 2. Register the Plugin
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

### 3. Run Installation Command
```bash
php artisan filtcms:install
```

That's it! Your CMS is ready! 🎉

## Admin Panel Access

Navigate to your Filament panel URL and you'll see a new **FiltCMS** navigation group with:

- 📄 **Pages** - Manage website pages
- 📁 **Categories** - Organize content
- 📰 **Blog** - Create blog posts
- 💬 **Comments** - Moderate comments
- ⚙️ **Settings** - Configure CMS

## Create Your First Page

1. Click **FiltCMS > Pages**
2. Click **New Page**
3. Enter title: "About Us"
4. Add content in the rich editor
5. Click **Save**

## Create Your First Blog Post

1. Click **FiltCMS > Blog**
2. Click **New Blog**
3. Enter title: "My First Post"
4. Add content
5. Toggle **Trending** if desired
6. Click **Save**

## Display Content on Frontend

### Option 1: Blade Components
```blade
{{-- In any Blade file --}}
<x-filtcms::page-content slug="about-us" />

<x-filtcms::blog-content slug="my-first-post" />

<x-filtcms::latest-blogs :limit="5" />
```

### Option 2: Facade Methods
```blade
@php
    $page = app(\EthickS\FiltCMS\FiltCMS::class)->page('about-us');
@endphp

@if($page->exists())
    <h1>{{ $page->getTitle() }}</h1>
    <div>{!! $page->getBody() !!}</div>
@endif
```

### Option 3: Public Routes
Content is automatically accessible at:
- `/blog` - Blog index
- `/blog/my-first-post` - Blog post
- `/about-us` - Page

## Enable Scheduled Publishing (Optional)

Add to `app/Console/Kernel.php`:

```php
use EthickS\FiltCMS\Models\{Blog, Page};

protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        Blog::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);
            
        Page::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->update(['status' => 'published']);
    })->everyMinute();
}
```

## Common Tasks

### Create a Category
1. Go to **FiltCMS > Categories**
2. Click **New Category**
3. Enter name (e.g., "Technology")
4. Optionally select a parent category
5. Save

### Moderate Comments
1. Go to **FiltCMS > Comments**
2. Use tabs: All, Approved, Pending, Rejected, Spam, Flagged
3. Click action menu on any comment
4. Choose: Approve, Reject, Spam, or Reply

### Configure Settings
1. Go to **FiltCMS > Settings**
2. Use tabs to configure:
   - **General** - Comment & blog settings
   - **SEO** - Default meta tags
   - **Social Media** - Social links
   - **Notifications** - Email settings
   - **Advanced** - Custom CSS/JS
3. Click **Save Settings**

## Tips

- **Auto-Slugs**: Leave slug empty to auto-generate from title
- **SEO**: Fill SEO fields for better search engine visibility
- **Scheduling**: Set status to "Scheduled" and pick future date
- **Categories**: Create hierarchy by selecting parent categories
- **Images**: Use the image editor to crop/resize featured images
- **Comments**: Enable profanity filter in settings for auto-moderation

## Need Help?

- Check the full [README.md](README.md) for detailed documentation
- See [IMPLEMENTATION.md](IMPLEMENTATION.md) for technical details

Enjoy your new CMS! 🚀
