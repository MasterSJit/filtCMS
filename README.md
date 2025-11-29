# FiltCMS - Full-Featured CMS Plugin for Filament PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ethicks/filtcms.svg?style=flat-square)](https://packagist.org/packages/ethicks/filtcms)
[![Total Downloads](https://img.shields.io/packagist/dt/ethicks/filtcms.svg?style=flat-square)](https://packagist.org/packages/ethicks/filtcms)

A comprehensive Content Management System plugin for Filament PHP v3, providing a complete solution for managing pages, blogs, categories, and comments with built-in SEO features.

## Features

### 📄 Pages Management
- Create, edit, and delete pages
- Rich text editor `(CKEditor/TinyMCE comming support soon)`
- Featured image uploads
- SEO meta tags (title, description, keywords)
- URL slug auto-generation
- Publish status (Draft, Published, Scheduled)
- Category assignment
- View, likes, and comments tracking
- Show/hide columns in list view
- Inline editing and bulk actions

### 📁 Categories Management
- Hierarchical category structure (parent/child)
- Drag-and-drop reordering
- Category images
- SEO optimization per category
- Used for both pages and blogs

### 📰 Blog Management
- Full blog post creation and management
- Rich content editor
- Excerpt support
- Featured images with image editor
- SEO meta tags
- Tags support
- Trending and Featured toggles
- Scheduled publishing
- Author tracking
- Views, likes, and comments statistics
- Stats widgets (Total Posts, Published, Drafts, Scheduled, Views, Likes)
- Filter by status, category, trending, featured

### 💬 Comments Management
- Comment moderation system
- Approve/Disapprove/Spam marking
- Profanity filter (auto-flag inappropriate content)
- Reply to comments
- Comment threading (nested replies)
- Author tracking (logged-in users or guests)
- IP and User Agent tracking
- Bulk actions for moderation

### ⚙️ Settings Page
- **General Settings**
  - Comment settings (enable/disable, moderation, notifications)
  - Blog settings (posts per page, guest posts, default status)
  - Page settings (templates, comments)
- **SEO Settings**
  - Default meta title, description, keywords
- **Social Media Settings**
  - Social media links
  - Share buttons configuration
- **Notification Settings**
  - Email notifications for comments and posts
- **Advanced Settings**
  - Custom CSS/JS injection

### 🌐 Frontend Features
- Public blog and page viewing
- Category/subcategory URL structure
- Automatic 404 handling for unpublished content
- Blade components for easy integration
- Facade methods for content retrieval

## Installation

You can install the package via composer:

```bash
composer require ethicks/filtcms
```

Run the installation command:

```bash
php artisan filtcms:install && npm install && npm run build
```

This will:
1. Publish the configuration file
2. Publish the migrations
3. Optionally run migrations

## Configuration

Register the plugin in your Filament panel provider (e.g., `app/Providers/Filament/AdminPanelProvider.php` or your custom panel provider name):

```php
use EthickS\FiltCMS\FiltCMSPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            FiltCMSPlugin::make(),
        ]);
}
```

## Usage

### Admin Panel

After installation, you'll see a new "FiltCMS" navigation group with:
1. Pages
2. Categories
3. Blog
4. Comments
5. Settings

### Creating Content

#### Pages
```
1. Navigate to FiltCMS > Pages
2. Click "New Page"
3. Fill in title (slug auto-generates)
4. Add content using the rich editor
5. Upload featured image
6. Configure SEO settings in the SEO tab
7. Set publish status and date
8. Assign to category (optional)
9. Save
```

#### Blog Posts
```
1. Navigate to FiltCMS > Blog
2. Click "New Blog"
3. Fill in title and content
4. Add excerpt and featured image
5. Configure SEO, tags, and settings
6. Toggle trending/featured if needed
7. Schedule or publish immediately
8. Save
```

#### Using Facade Methods

```php
use EthickS\FiltCMS\Facades\FiltCMS;

// Get a page
$page = FiltCMS::page('about-us');

if ($page->exists()) {
    echo $page->getTitle();
    echo $page->getBody();
    echo $page->getExcerpt();
    echo $page->getFeaturedImage();
    echo $page->getViewsCount();
    echo $page->getLikesCount();
    echo $page->getCommentsCount();
}

// Get a blog post
$blog = FiltCMS::blog('my-post-slug');

if ($blog->exists()) {
    echo $blog->getTitle();
    echo $blog->getBody();
    print_r($blog->getTags());
    echo $blog->getCategory()->name;
    echo $blog->getAuthor()->name;
}
```
For more methods, refer to the [FILTCMS_API.md](FILTCMS_API.md) documentation.

#### Blade Template Example

```php
@php
    $filtCms = app(\EthickS\FiltCMS\FiltCMS::class)->page('about-us');
@endphp

@if($filtCms->exists())
    <h1>{{ $filtCms->getTitle() }}</h1>
    <div>{!! $filtCms->getBody() !!}</div>
@endif
```

### Comment System

Comments are automatically displayed on published blogs and pages (if enabled). The comment form includes:
- Profanity filtering
- Moderation queue
- Guest or authenticated user comments
- Reply threading

### Scheduling Posts

1. Set status to "Scheduled"
2. Set publish date to future date
3. Add this to your `app/Console/Kernel.php`:

```php
use EthickS\FiltCMS\Models\Blog;
use EthickS\FiltCMS\Models\Page;

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
Please see [CRON_SETUP](CRON_SETUP.md) for more information on what has changed recently.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.


## Credits

- [Master S Jit](https://github.com/MasterSJit)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
