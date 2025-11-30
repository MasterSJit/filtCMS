<?php

namespace EthickS\FiltCMS\Services;

use ArrayAccess;
use EthickS\FiltCMS\Models\Blog;
use EthickS\FiltCMS\Models\Category;
use EthickS\FiltCMS\Models\Comment;
use EthickS\FiltCMS\Models\Page;
use Illuminate\Support\Collection;

class FiltCMSService implements ArrayAccess
{
    protected array $cache = [];
    
    protected ?string $defaultSlug = null;
    
    protected ?string $contentType = null; // 'blog', 'page', or 'category'

    /**
     * Create a new instance with a pre-set slug
     * 
     * @param string $slug The default slug to use
     * @param string $type Content type: 'blog', 'page', or 'category' (auto-detected if not specified)
     */
    public static function get(string $slug, ?string $type = null): self
    {
        $instance = new self();
        $instance->defaultSlug = $slug;
        $instance->contentType = $type;
        
        // Auto-detect content type if not specified
        if ($type === null) {
            if (Blog::where('slug', $slug)->exists()) {
                $instance->contentType = 'blog';
            } elseif (Page::where('slug', $slug)->exists()) {
                $instance->contentType = 'page';
            } elseif (Category::where('slug', $slug)->exists()) {
                $instance->contentType = 'category';
            }
        }
        
        return $instance;
    }

    /**
     * Set the default slug for this instance
     */
    public function for(string $slug, ?string $type = null): self
    {
        $this->defaultSlug = $slug;
        $this->contentType = $type;
        
        // Auto-detect content type if not specified
        if ($type === null) {
            if (Blog::where('slug', $slug)->exists()) {
                $this->contentType = 'blog';
            } elseif (Page::where('slug', $slug)->exists()) {
                $this->contentType = 'page';
            } elseif (Category::where('slug', $slug)->exists()) {
                $this->contentType = 'category';
            }
        }
        
        return $this;
    }

    /**
     * Get the slug to use, either passed or default
     */
    protected function resolveSlug(?string $slug): ?string
    {
        return $slug ?? $this->defaultSlug;
    }

    // Blog Methods
    public function blog(?string $slug = null): ?Blog
    {
        $slug = $this->resolveSlug($slug);
        if (!$slug) return null;
        return $this->cache['blog.' . $slug] ??= Blog::where('slug', $slug)->published()->first();
    }

    public function blogTitle(?string $slug = null): ?string
    {
        return $this->blog($slug)?->title;
    }

    public function blogBody(?string $slug = null): ?string
    {
        return $this->blog($slug)?->body;
    }

    public function blogExcerpt(?string $slug = null): ?string
    {
        return $this->blog($slug)?->excerpt;
    }

    public function blogImage(?string $slug = null): ?string
    {
        return $this->blog($slug)?->featured_image;
    }

    public function blogSeoTitle(?string $slug = null): ?string
    {
        return $this->blog($slug)?->seo_title;
    }

    public function blogSeoDescription(?string $slug = null): ?string
    {
        return $this->blog($slug)?->seo_description;
    }

    public function blogSeoKeywords(?string $slug = null): ?string
    {
        return $this->blog($slug)?->seo_keywords;
    }

    public function blogCategory(?string $slug = null): ?Category
    {
        return $this->blog($slug)?->category;
    }

    public function blogAuthor(?string $slug = null)
    {
        return $this->blog($slug)?->author;
    }

    public function blogViews(?string $slug = null): int
    {
        return $this->blog($slug)?->views_count ?? 0;
    }

    public function blogLikes(?string $slug = null): int
    {
        return $this->blog($slug)?->likes_count ?? 0;
    }

    public function blogTags(?string $slug = null): ?array
    {
        return $this->blog($slug)?->tags;
    }

    // Generic accessors that use content type detection
    public function title(?string $slug = null): ?string
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogTitle($slug),
            'page' => $this->pageTitle($slug),
            'category' => $this->categoryName($slug),
            default => $this->blogTitle($slug) ?? $this->pageTitle($slug) ?? $this->categoryName($slug),
        };
    }

    public function body(?string $slug = null): ?string
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogBody($slug),
            'page' => $this->pageBody($slug),
            'category' => $this->categoryDescription($slug),
            default => $this->blogBody($slug) ?? $this->pageBody($slug) ?? $this->categoryDescription($slug),
        };
    }

    public function excerpt(?string $slug = null): ?string
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogExcerpt($slug),
            'page' => $this->pageExcerpt($slug),
            default => $this->blogExcerpt($slug) ?? $this->pageExcerpt($slug),
        };
    }

    public function image(?string $slug = null): ?string
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogImage($slug),
            'page' => $this->pageImage($slug),
            'category' => $this->categoryImage($slug),
            default => $this->blogImage($slug) ?? $this->pageImage($slug) ?? $this->categoryImage($slug),
        };
    }

    public function seoTitle(?string $slug = null): ?string
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogSeoTitle($slug),
            'page' => $this->pageSeoTitle($slug),
            'category' => $this->categorySeoTitle($slug),
            default => $this->blogSeoTitle($slug) ?? $this->pageSeoTitle($slug) ?? $this->categorySeoTitle($slug),
        };
    }

    public function seoDescription(?string $slug = null): ?string
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogSeoDescription($slug),
            'page' => $this->pageSeoDescription($slug),
            'category' => $this->categorySeoDescription($slug),
            default => $this->blogSeoDescription($slug) ?? $this->pageSeoDescription($slug) ?? $this->categorySeoDescription($slug),
        };
    }

    public function seoKeywords(?string $slug = null): ?string
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogSeoKeywords($slug),
            'page' => $this->pageSeoKeywords($slug),
            'category' => $this->categorySeoKeywords($slug),
            default => $this->blogSeoKeywords($slug) ?? $this->pageSeoKeywords($slug) ?? $this->categorySeoKeywords($slug),
        };
    }

    public function views(?string $slug = null): int
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogViews($slug),
            'page' => $this->pageViews($slug),
            default => $this->blogViews($slug) ?: $this->pageViews($slug),
        };
    }

    public function comments(?string $slug = null): Collection
    {
        $slug = $this->resolveSlug($slug);
        return match($this->contentType) {
            'blog' => $this->blogComments($slug),
            'page' => $this->pageComments($slug),
            default => $this->blogComments($slug)->isEmpty() ? $this->pageComments($slug) : $this->blogComments($slug),
        };
    }

    public function allBlogs(): Collection
    {
        return Blog::published()->orderBy('published_at', 'desc')->get();
    }

    public function latestBlogs(int $limit = 5): Collection
    {
        return Blog::published()->orderBy('published_at', 'desc')->limit($limit)->get();
    }

    public function trendingBlogs(int $limit = 5): Collection
    {
        return Blog::published()->where('is_trending', true)->orderBy('published_at', 'desc')->limit($limit)->get();
    }

    public function featuredBlogs(int $limit = 5): Collection
    {
        return Blog::published()->where('is_featured', true)->orderBy('published_at', 'desc')->limit($limit)->get();
    }

    // Page Methods
    public function page(?string $slug = null): ?Page
    {
        $slug = $this->resolveSlug($slug);
        if (!$slug) return null;
        return $this->cache['page.' . $slug] ??= Page::where('slug', $slug)->published()->first();
    }

    public function pageTitle(?string $slug = null): ?string
    {
        return $this->page($slug)?->title;
    }

    public function pageBody(?string $slug = null): ?string
    {
        return $this->page($slug)?->body;
    }

    public function pageExcerpt(?string $slug = null): ?string
    {
        return $this->page($slug)?->excerpt;
    }

    public function pageImage(?string $slug = null): ?string
    {
        return $this->page($slug)?->featured_image;
    }

    public function pageSeoTitle(?string $slug = null): ?string
    {
        return $this->page($slug)?->seo_title;
    }

    public function pageSeoDescription(?string $slug = null): ?string
    {
        return $this->page($slug)?->seo_description;
    }

    public function pageSeoKeywords(?string $slug = null): ?string
    {
        return $this->page($slug)?->seo_keywords;
    }

    public function pageCategory(?string $slug = null): ?Category
    {
        return $this->page($slug)?->category;
    }

    public function pageViews(?string $slug = null): int
    {
        return $this->page($slug)?->views_count ?? 0;
    }

    public function allPages(): Collection
    {
        return Page::published()->orderBy('published_at', 'desc')->get();
    }

    // Category Methods
    public function category(?string $slug = null): ?Category
    {
        $slug = $this->resolveSlug($slug);
        if (!$slug) return null;
        return $this->cache['category.' . $slug] ??= Category::where('slug', $slug)->first();
    }

    public function categoryName(?string $slug = null): ?string
    {
        return $this->category($slug)?->name;
    }

    public function categoryDescription(?string $slug = null): ?string
    {
        return $this->category($slug)?->description;
    }

    public function categoryImage(?string $slug = null): ?string
    {
        return $this->category($slug)?->image;
    }

    public function categorySeoTitle(?string $slug = null): ?string
    {
        return $this->category($slug)?->seo_title;
    }

    public function categorySeoDescription(?string $slug = null): ?string
    {
        return $this->category($slug)?->seo_description;
    }

    public function categorySeoKeywords(?string $slug = null): ?string
    {
        return $this->category($slug)?->seo_keywords;
    }

    public function categoryBlogs(?string $slug = null): Collection
    {
        return $this->category($slug)?->blogs()->published()->get() ?? collect();
    }

    public function categoryPages(?string $slug = null): Collection
    {
        return $this->category($slug)?->pages()->published()->get() ?? collect();
    }

    public function categoryChildren(?string $slug = null): Collection
    {
        return $this->category($slug)?->children ?? collect();
    }

    public function allCategories(): Collection
    {
        return Category::orderBy('order', 'asc')->get();
    }

    public function topCategories(): Collection
    {
        return Category::whereNull('parent_id')->orderBy('order', 'asc')->get();
    }

    // Comment Methods
    public function blogComments(?string $slug = null): Collection
    {
        $slug = $this->resolveSlug($slug);
        $blog = $this->blog($slug);
        if (! $blog) {
            return collect();
        }

        return Comment::where('commentable_type', Blog::class)
            ->where('commentable_id', $blog->id)
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->with('replies')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function pageComments(?string $slug = null): Collection
    {
        $slug = $this->resolveSlug($slug);
        $page = $this->page($slug);
        if (! $page) {
            return collect();
        }

        return Comment::where('commentable_type', Page::class)
            ->where('commentable_id', $page->id)
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->with('replies')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function commentContent(int $id): ?string
    {
        return Comment::find($id)?->content;
    }

    public function commentAuthor(int $id): ?string
    {
        $comment = Comment::find($id);

        return $comment?->user?->name ?? $comment?->author_name;
    }

    // ArrayAccess Implementation
    public function offsetExists($offset): bool
    {
        return method_exists($this, $offset);
    }

    public function offsetGet($offset): mixed
    {
        if (method_exists($this, $offset)) {
            return $this->$offset();
        }

        return null;
    }

    public function offsetSet($offset, $value): void
    {
        // Read-only
    }

    public function offsetUnset($offset): void
    {
        // Read-only
    }

    // Clear cache
    public function clearCache(): void
    {
        $this->cache = [];
    }
}
