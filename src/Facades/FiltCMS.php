<?php

namespace EthickS\FiltCMS\Facades;

use EthickS\FiltCMS\Services\FiltCMSService;
use Illuminate\Support\Facades\Facade;

/**
 * @see \EthickS\FiltCMS\Services\FiltCMSService
 *
 * @method static FiltCMSService get(string $slug, ?string $type = null) Create instance with pre-set slug
 * @method static FiltCMSService for(string $slug, ?string $type = null) Set default slug for chaining
 * @method static string|null title(?string $slug = null) Get title (auto-detects content type)
 * @method static string|null body(?string $slug = null) Get body/content (auto-detects content type)
 * @method static string|null excerpt(?string $slug = null) Get excerpt (auto-detects content type)
 * @method static string|null image(?string $slug = null) Get image (auto-detects content type)
 * @method static string|null seoTitle(?string $slug = null) Get SEO title (auto-detects content type)
 * @method static string|null seoDescription(?string $slug = null) Get SEO description (auto-detects content type)
 * @method static string|null seoKeywords(?string $slug = null) Get SEO keywords (auto-detects content type)
 * @method static int views(?string $slug = null) Get views count (auto-detects content type)
 * @method static \Illuminate\Support\Collection comments(?string $slug = null) Get comments (auto-detects content type)
 * @method static \EthickS\FiltCMS\Models\Blog|null blog(?string $slug = null)
 * @method static string|null blogTitle(?string $slug = null)
 * @method static string|null blogBody(?string $slug = null)
 * @method static string|null blogExcerpt(?string $slug = null)
 * @method static string|null blogImage(?string $slug = null)
 * @method static string|null blogSeoTitle(?string $slug = null)
 * @method static string|null blogSeoDescription(?string $slug = null)
 * @method static string|null blogSeoKeywords(?string $slug = null)
 * @method static \EthickS\FiltCMS\Models\Category|null blogCategory(?string $slug = null)
 * @method static mixed blogAuthor(?string $slug = null)
 * @method static int blogViews(?string $slug = null)
 * @method static int blogLikes(?string $slug = null)
 * @method static array|null blogTags(?string $slug = null)
 * @method static \Illuminate\Support\Collection allBlogs()
 * @method static \Illuminate\Support\Collection latestBlogs(int $limit = 5)
 * @method static \Illuminate\Support\Collection trendingBlogs(int $limit = 5)
 * @method static \Illuminate\Support\Collection featuredBlogs(int $limit = 5)
 * @method static \EthickS\FiltCMS\Models\Page|null page(?string $slug = null)
 * @method static string|null pageTitle(?string $slug = null)
 * @method static string|null pageBody(?string $slug = null)
 * @method static string|null pageExcerpt(?string $slug = null)
 * @method static string|null pageImage(?string $slug = null)
 * @method static string|null pageSeoTitle(?string $slug = null)
 * @method static string|null pageSeoDescription(?string $slug = null)
 * @method static string|null pageSeoKeywords(?string $slug = null)
 * @method static \EthickS\FiltCMS\Models\Category|null pageCategory(?string $slug = null)
 * @method static int pageViews(?string $slug = null)
 * @method static \Illuminate\Support\Collection allPages()
 * @method static \EthickS\FiltCMS\Models\Category|null category(?string $slug = null)
 * @method static string|null categoryName(?string $slug = null)
 * @method static string|null categoryDescription(?string $slug = null)
 * @method static string|null categoryImage(?string $slug = null)
 * @method static string|null categorySeoTitle(?string $slug = null)
 * @method static string|null categorySeoDescription(?string $slug = null)
 * @method static string|null categorySeoKeywords(?string $slug = null)
 * @method static \Illuminate\Support\Collection categoryBlogs(?string $slug = null)
 * @method static \Illuminate\Support\Collection categoryPages(?string $slug = null)
 * @method static \Illuminate\Support\Collection categoryChildren(?string $slug = null)
 * @method static \Illuminate\Support\Collection allCategories()
 * @method static \Illuminate\Support\Collection topCategories()
 * @method static \Illuminate\Support\Collection blogComments(?string $slug = null)
 * @method static \Illuminate\Support\Collection pageComments(?string $slug = null)
 * @method static string|null commentContent(int $id)
 * @method static string|null commentAuthor(int $id)
 * @method static void clearCache()
 */
class FiltCMS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filtcms';
    }
}
