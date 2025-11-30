# FiltCMS API Documentation

## Usage Examples

### Static Facade Access

```php
use EthickS\FiltCMS\Facades\FiltCMS;
$title = FiltCMS::blogTitle('my-blog-post');
$body = FiltCMS::blogBody('my-blog-post');
$excerpt = FiltCMS::blogExcerpt('my-blog-post');
$image = FiltCMS::blogImage('my-blog-post');
$seoTitle = FiltCMS::blogSeoTitle('my-blog-post');
$category = FiltCMS::blogCategory('my-blog-post');
$author = FiltCMS::blogAuthor('my-blog-post');
$views = FiltCMS::blogViews('my-blog-post');
$likes = FiltCMS::blogLikes('my-blog-post');
$tags = FiltCMS::blogTags('my-blog-post');

// Get collections
$allBlogs = FiltCMS::allBlogs();
$latestBlogs = FiltCMS::latestBlogs(5);
$trendingBlogs = FiltCMS::trendingBlogs(10);
$featuredBlogs = FiltCMS::featuredBlogs(3);

// Page Methods
$pageTitle = FiltCMS::pageTitle('about-us');
$pageBody = FiltCMS::pageBody('about-us');
$pageImage = FiltCMS::pageImage('about-us');
$allPages = FiltCMS::allPages();

// Category Methods
$categoryName = FiltCMS::categoryName('technology');
$categoryDescription = FiltCMS::categoryDescription('technology');
$categoryImage = FiltCMS::categoryImage('technology');
$categoryBlogs = FiltCMS::categoryBlogs('technology');
$categoryPages = FiltCMS::categoryPages('technology');
$categoryChildren = FiltCMS::categoryChildren('technology');
$allCategories = FiltCMS::allCategories();
$topCategories = FiltCMS::topCategories();

// Comment Methods
$blogComments = FiltCMS::blogComments('my-blog-post');
$pageComments = FiltCMS::pageComments('about-us');
```
OR 

```php
// Blog Methods (traditional)

$title = FiltCMS::blogTitle('my-blog-post');
$body = FiltCMS::blogBody('my-blog-post');

$blog = FiltCMS::get('my-blog-post');
$title = $blog->title();
$body = $blog->body();
$excerpt = $blog->excerpt();
$image = $blog->image();
$views = $blog->views();
$comments = $blog->comments();

// Or use specific methods
$title = $blog->blogTitle();
$author = $blog->blogAuthor();
$tags = $blog->blogTags();

// Works with pages too
$page = FiltCMS::get('about-us');
$pageTitle = $page->title();
$pageBody = $page->body();

// Explicit type if needed
$page = FiltCMS::get('about-us', 'page');
$category = FiltCMS::get('technology', 'category');

```
### Helper Function Access

```php

// Initialize with slug
$blog = filtcms('my-blog-post');
$title = $blog->title();       // Gets title (auto-detects blog)
$body = $blog->body();         // Gets body
$excerpt = $blog->excerpt();   // Gets excerpt
$image = $blog->image();       // Gets featured image
$views = $blog->views();       // Gets view count
$comments = $blog->comments(); // Gets comments

// Works with pages
$page = filtcms('about-us');
$pageTitle = $page->title();
$pageBody = $page->body();

// Explicit type
$page = filtcms('about-us', 'page');
$category = filtcms('technology', 'category');

// Traditional usage (still works)
$filtcms = filtcms();
$title = $filtcms->blogTitle('my-blog-post');
$body = $filtcms->blogBody('my-blog-post');
$excerpt = $filtcms->blogExcerpt('my-blog-post');
$image = $filtcms->blogImage('my-blog-post');
$seoTitle = $filtcms->blogSeoTitle('my-blog-post');
$category = $filtcms->blogCategory('my-blog-post');
$author = $filtcms->blogAuthor('my-blog-post');
$views = $filtcms->blogViews('my-blog-post');
$likes = $filtcms->blogLikes('my-blog-post');
$tags = $filtcms->blogTags('my-blog-post');

// Using for() method for chaining
$blog = filtcms()->for('my-blog-post');
$title = $blog->title();
$body = $blog->body();


// Get collections
$allBlogs = $filtcms->allBlogs();
$latestBlogs = $filtcms->latestBlogs(5);
$trendingBlogs = $filtcms->trendingBlogs(10);
$featuredBlogs = $filtcms->featuredBlogs(3);

// Page Methods
$pageTitle = $filtcms->pageTitle('about-us');
$pageBody = $filtcms->pageBody('about-us');
$pageImage = $filtcms->pageImage('about-us');
$allPages = $filtcms->allPages();

// Category Methods
$categoryName = $filtcms->categoryName('technology');
$categoryDescription = $filtcms->categoryDescription('technology');
$categoryImage = $filtcms->categoryImage('technology');
$categoryBlogs = $filtcms->categoryBlogs('technology');
$categoryPages = $filtcms->categoryPages('technology');
$categoryChildren = $filtcms->categoryChildren('technology');
$allCategories = $filtcms->allCategories();
$topCategories = $filtcms->topCategories();

// Comment Methods
$blogComments = $filtcms->blogComments('my-blog-post');
$pageComments = $filtcms->pageComments('about-us');
```

## API Endpoints

All API endpoints are read-only and return JSON responses.

### Base URL
```
/api/filtcms
```

### Blogs

#### List All Blogs
```
GET /api/filtcms/blogs

Query Parameters:
- category (string): Filter by category slug
- trending (boolean): Filter trending blogs
- featured (boolean): Filter featured blogs
- search (string): Search in title, body, excerpt
- per_page (int): Items per page (max 100, default 15)
- page (int): Page number

Example:
GET /api/filtcms/blogs?category=technology&trending=1&per_page=10
```

Response:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Blog Title",
            "slug": "blog-title",
            "body": "Content...",
            "excerpt": "Excerpt...",
            "featured_image": "path/to/image.jpg",
            "category_id": 1,
            "status": "published",
            "published_at": "2025-11-27T00:00:00.000000Z",
            "is_trending": true,
            "is_featured": false,
            "views_count": 150,
            "likes_count": 25,
            "tags": ["tag1", "tag2"]
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    }
}
```

#### Get Single Blog
```
GET /api/filtcms/blogs/{slug}

Example:
GET /api/filtcms/blogs/my-blog-post
```

### Pages

#### List All Pages
```
GET /api/filtcms/pages

Query Parameters:
- category (string): Filter by category slug
- search (string): Search in title, body, excerpt
- per_page (int): Items per page (max 100, default 15)
- page (int): Page number
```

#### Get Single Page
```
GET /api/filtcms/pages/{slug}
```

### Categories

#### List All Categories
```
GET /api/filtcms/categories

Query Parameters:
- top_only (boolean): Only top-level categories
- parent (string): Filter by parent category slug
- with_counts (boolean): Include pages and blogs counts
```

#### Get Single Category
```
GET /api/filtcms/categories/{slug}

Query Parameters:
- with_children (boolean): Include child categories
- with_blogs (boolean): Include blogs
- with_pages (boolean): Include pages
- with_counts (boolean): Include counts
```

### Comments

#### List All Comments
```
GET /api/filtcms/comments

Query Parameters:
- type (string): 'blog' or 'page'
- content_slug (string): Filter by blog/page slug
- parents_only (boolean): Only parent comments (no replies)
- per_page (int): Items per page (max 100, default 20)
- page (int): Page number
```

#### Get Single Comment
```
GET /api/filtcms/comments/{id}
```

## Setup

1. Run composer dump-autoload:
```bash
composer dump-autoload
```

2. The facade is automatically registered and available as:
```php
use App\Facades\FiltCMS;
```

3. Helper function is available globally:
```php
filtcms()->blogTitle('slug');
```

## Cache Clearing

To clear the internal cache:
```php
FiltCMS::clearCache();
// or
filtcms()->clearCache();
```
