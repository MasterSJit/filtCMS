# FiltCMS Plugin

### Database Migrations (5 tables)
1. **filtcms_categories** - Hierarchical categories with SEO
2. **filtcms_pages** - Pages with full CMS features
3. **filtcms_blogs** - Blog posts with advanced features
4. **filtcms_comments** - Comment system with moderation
5. **filtcms_settings** - Plugin settings storage

### Models (5 Eloquent Models)
1. **Category** - With parent/child relationships, auto-slug generation
2. **Page** - With scopes, relationships, view tracking
3. **Blog** - With tags, trending/featured flags, scheduling
4. **Comment** - With profanity filter, moderation, threading
5. **Setting** - With type casting and helper methods

### Filament Resources (4 Complete Resources)

#### 1. PageResource
- ✅ List view with tabs (All, Published, Draft, Scheduled)
- ✅ Show/hide columns
- ✅ Filters (status, category, trashed)
- ✅ Bulk actions (delete, restore, force delete)
- ✅ Form with sections (Content, Settings, SEO)
- ✅ Rich text editor
- ✅ Featured image upload with editor
- ✅ SEO fields (title, description, keywords)
- ✅ Auto-slug generation
- ✅ Category selection with inline create
- ✅ Publish status and scheduling
- ✅ Infolist for viewing stats (views, likes, comments)

#### 2. CategoryResource
- ✅ List view with parent/child display
- ✅ Drag-and-drop reordering
- ✅ Parent category selection
- ✅ Category images
- ✅ SEO optimization
- ✅ Bulk actions
- ✅ Auto-slug generation

#### 3. BlogResource
- ✅ Stats widgets (Total Posts, Published, Drafts, Scheduled, Views, Likes)
- ✅ Tabs interface (Content, Settings, SEO)
- ✅ Rich text editor
- ✅ Featured image upload
- ✅ Tags support
- ✅ Trending/Featured toggles
- ✅ Scheduled publishing
- ✅ Author tracking
- ✅ Category assignment
- ✅ List view with 6 tabs (All, Published, Draft, Scheduled, Trending, Featured)
- ✅ Toggle columns for trending/featured
- ✅ Stats display (views, likes, comments)
- ✅ Comprehensive Infolist with author info and statistics

#### 4. CommentResource
- ✅ List view with tabs (All, Approved, Pending, Rejected, Spam, Flagged)
- ✅ Moderation actions (Approve, Reject, Mark as Spam)
- ✅ Reply functionality
- ✅ Profanity filter integration
- ✅ Author tracking (user or guest)
- ✅ IP and User Agent logging
- ✅ Bulk moderation actions
- ✅ Polymorphic relationship to blogs/pages

### Settings Page
- ✅ **General Settings Tab**
  - Comment settings (enable/disable, moderation, notifications, profanity filter)
  - Blog settings (posts per page, guest posts, default status)
  - Page settings (template, comments)
- ✅ **SEO Settings Tab**
  - Default meta tags
- ✅ **Social Media Tab**
  - Social links (Facebook, Twitter, Instagram, LinkedIn)
  - Share buttons toggle
- ✅ **Notifications Tab**
  - Email notification settings
- ✅ **Advanced Tab**
  - Custom CSS/JS injection


#### Facade Methods
```php
FiltCMS::page('slug')->getTitle()
FiltCMS::page('slug')->getBody()
FiltCMS::page('slug')->getExcerpt()
FiltCMS::blog('slug')->getTags()
FiltCMS::blog('slug')->getAuthor()
// And many more...
```

### Routes
- ✅ `/blog` - Blog index
- ✅ `/blog/{category}/{slug}` - Blog post with category path
- ✅ `/{category}/{slug}` - Page with category path
- ✅ `POST /filtcms/comments` - Comment submission

### Configuration
- ✅ Comprehensive config file with all settings
- ✅ Profanity words list
- ✅ Default values for all features

### Additional Features Implemented

1. **Auto-Slug Generation** - Automatically creates SEO-friendly URLs
2. **View Tracking** - Increments view count on page/blog visits
3. **Scheduled Publishing** - Posts can be scheduled for future publication
4. **Comment Threading** - Nested replies to comments
5. **Profanity Filter** - Auto-flags inappropriate comments
6. **SEO Optimization** - Meta tags for all content types
7. **Image Management** - Featured images with built-in editor
8. **Bulk Actions** - Multi-select operations on all resources
9. **Soft Deletes** - Trash and restore functionality
10. **Author Attribution** - Links content to users

## 📋 Installation Instructions

1. **Add to Panel Provider:**
```php
use EthickS\FiltCMS\FiltCMSPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FiltCMSPlugin::make(),
        ]);
}
```

2. **Run Installation:**
```bash
php artisan filtcms:install
```

3. **For Scheduled Posts (Optional):**
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

## 🎯 Usage Examples

### Admin Panel
Simply navigate to "FiltCMS" in your Filament panel to manage:
- Pages
- Categories  
- Blog Posts
- Comments
- Settings

### Frontend Display

**Using Blade Components:**
```blade
<x-filtcms::page-content slug="about-us" />
<x-filtcms::blog-content slug="my-post" />
<x-filtcms::latest-blogs :limit="5" />
```

**Using Facade:**
```blade
@php
    $page = app(\EthickS\FiltCMS\FiltCMS::class)->page('about-us');
@endphp

@if($page->exists())
    <h1>{{ $page->getTitle() }}</h1>
    <div>{!! $page->getBody() !!}</div>
@endif
```

## 🎨 Customization

### Publish Views:
```bash
php artisan vendor:publish --tag=filtcms-views
```

### Publish Config:
```bash
php artisan vendor:publish --tag=filtcms-config
```

### Add Custom CSS/JS:
Use the Settings page (Advanced tab) or edit `config/filtcms.php`

## ✨ All Requirements Met

✅ Pages with schema, tabs, infolist, filters, bulk actions  
✅ Categories with hierarchy, drag-drop, SEO  
✅ Blog with stats widgets, trending, featured, scheduling  
✅ Comments with moderation, profanity filter, threading  
✅ Settings with all tabs (General, SEO, Social, Notifications, Advanced)  
✅ Public URLs with category/subcategory structure  
✅ Blade components and facade methods  
✅ 404 handling for unpublished content  
✅ Rich text editor support  
✅ Image uploads and management  
✅ Auto-slug generation  
✅ View/likes/comments tracking  

Your FiltCMS plugin is now complete and ready to use! 🚀
