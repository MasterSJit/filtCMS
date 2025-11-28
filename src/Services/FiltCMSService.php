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

    // Blog Methods
    public function blog(string $slug): ?Blog
    {
        return $this->cache['blog.' . $slug] ??= Blog::where('slug', $slug)->published()->first();
    }

    public function blogTitle(string $slug): ?string
    {
        return $this->blog($slug)?->title;
    }

    public function blogBody(string $slug): ?string
    {
        return $this->blog($slug)?->body;
    }

    public function blogExcerpt(string $slug): ?string
    {
        return $this->blog($slug)?->excerpt;
    }

    public function blogImage(string $slug): ?string
    {
        return $this->blog($slug)?->featured_image;
    }

    public function blogSeoTitle(string $slug): ?string
    {
        return $this->blog($slug)?->seo_title;
    }

    public function blogSeoDescription(string $slug): ?string
    {
        return $this->blog($slug)?->seo_description;
    }

    public function blogSeoKeywords(string $slug): ?string
    {
        return $this->blog($slug)?->seo_keywords;
    }

    public function blogCategory(string $slug): ?Category
    {
        return $this->blog($slug)?->category;
    }

    public function blogAuthor(string $slug)
    {
        return $this->blog($slug)?->author;
    }

    public function blogViews(string $slug): int
    {
        return $this->blog($slug)?->views_count ?? 0;
    }

    public function blogLikes(string $slug): int
    {
        return $this->blog($slug)?->likes_count ?? 0;
    }

    public function blogTags(string $slug): ?array
    {
        return $this->blog($slug)?->tags;
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
    public function page(string $slug): ?Page
    {
        return $this->cache['page.' . $slug] ??= Page::where('slug', $slug)->published()->first();
    }

    public function pageTitle(string $slug): ?string
    {
        return $this->page($slug)?->title;
    }

    public function pageBody(string $slug): ?string
    {
        return $this->page($slug)?->body;
    }

    public function pageExcerpt(string $slug): ?string
    {
        return $this->page($slug)?->excerpt;
    }

    public function pageImage(string $slug): ?string
    {
        return $this->page($slug)?->featured_image;
    }

    public function pageSeoTitle(string $slug): ?string
    {
        return $this->page($slug)?->seo_title;
    }

    public function pageSeoDescription(string $slug): ?string
    {
        return $this->page($slug)?->seo_description;
    }

    public function pageSeoKeywords(string $slug): ?string
    {
        return $this->page($slug)?->seo_keywords;
    }

    public function pageCategory(string $slug): ?Category
    {
        return $this->page($slug)?->category;
    }

    public function pageViews(string $slug): int
    {
        return $this->page($slug)?->views_count ?? 0;
    }

    public function allPages(): Collection
    {
        return Page::published()->orderBy('published_at', 'desc')->get();
    }

    // Category Methods
    public function category(string $slug): ?Category
    {
        return $this->cache['category.' . $slug] ??= Category::where('slug', $slug)->first();
    }

    public function categoryName(string $slug): ?string
    {
        return $this->category($slug)?->name;
    }

    public function categoryDescription(string $slug): ?string
    {
        return $this->category($slug)?->description;
    }

    public function categoryImage(string $slug): ?string
    {
        return $this->category($slug)?->image;
    }

    public function categorySeoTitle(string $slug): ?string
    {
        return $this->category($slug)?->seo_title;
    }

    public function categorySeoDescription(string $slug): ?string
    {
        return $this->category($slug)?->seo_description;
    }

    public function categorySeoKeywords(string $slug): ?string
    {
        return $this->category($slug)?->seo_keywords;
    }

    public function categoryBlogs(string $slug): Collection
    {
        return $this->category($slug)?->blogs()->published()->get() ?? collect();
    }

    public function categoryPages(string $slug): Collection
    {
        return $this->category($slug)?->pages()->published()->get() ?? collect();
    }

    public function categoryChildren(string $slug): Collection
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
    public function blogComments(string $slug): Collection
    {
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

    public function pageComments(string $slug): Collection
    {
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
