# FiltCMS - Quick Start Guide

## Installation

### For Published Package (from Packagist)

#### 1. Install the Package
```bash
composer require ethicks/filtcms
```

---

## Configuration (Both Methods)

### 2. Register the Plugin
Edit `app/Providers/Filament/AdminPanelProvider.php` or your custom panel provider name:

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

----
`Under Development`
## Display Content on Frontend

### Option 1: Blade Components
```blade
{{-- In any Blade file --}}
<x-filtcms::page-content slug="about-us" />

<x-filtcms::blog-content slug="my-first-post" />

<x-filtcms::latest-blogs :limit="5" />
```
----

### Option 2: Facade Methods
```php
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
- `/__about-us__` - Page

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

## Tips

- **Auto-Slugs**: Leave slug empty to auto-generate from title
- **SEO**: Fill SEO fields for better search engine visibility
- **Scheduling**: Set status to "Scheduled" and pick future date
- **Categories**: Create hierarchy by selecting parent categories
- **Images**: Use the image editor to crop/resize featured images
- **Comments**: Enable profanity filter in settings for auto-moderation

## Need Help?

- Check the full [README.md](README.md) for detailed documentation

Enjoy your new CMS! 🚀
