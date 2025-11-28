<?php

namespace EthickS\FiltCMS\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \EthickS\FiltCMS\FiltCMS
 *
 * @method static \EthickS\FiltCMS\Blog|null blog(string $slug)
 * @method static string|null blogTitle(string $slug)
 * @method static string|null blogBody(string $slug)
 * @method static string|null blogExcerpt(string $slug)
 * @method static string|null blogImage(string $slug)
 * @method static string|null blogSeoTitle(string $slug)
 * @method static string|null blogSeoDescription(string $slug)
 * @method static string|null blogSeoKeywords(string $slug)
 * @method static \EthickS\FiltCMS\Category|null blogCategory(string $slug)
 * @method static \EthickS\FiltCMS\User|null blogAuthor(string $slug)
 * @method static int blogViews(string $slug)
 * @method static int blogLikes(string $slug)
 * @method static array|null blogTags(string $slug)
 * @method static \Illuminate\Support\Collection allBlogs()
 * @method static \Illuminate\Support\Collection latestBlogs(int $limit = 5)
 * @method static \Illuminate\Support\Collection trendingBlogs(int $limit = 5)
 * @method static \Illuminate\Support\Collection featuredBlogs(int $limit = 5)
 * @method static \EthickS\FiltCMS\Page|null page(string $slug)
 * @method static string|null pageTitle(string $slug)
 * @method static string|null pageBody(string $slug)
 * @method static string|null pageExcerpt(string $slug)
 * @method static string|null pageImage(string $slug)
 * @method static string|null pageSeoTitle(string $slug)
 * @method static string|null pageSeoDescription(string $slug)
 * @method static string|null pageSeoKeywords(string $slug)
 * @method static \EthickS\FiltCMS\Category|null pageCategory(string $slug)
 * @method static int pageViews(string $slug)
 * @method static \Illuminate\Support\Collection allPages()
 * @method static \EthickS\FiltCMS\Category|null category(string $slug)
 * @method static string|null categoryName(string $slug)
 * @method static string|null categoryDescription(string $slug)
 * @method static string|null categoryImage(string $slug)
 * @method static string|null categorySeoTitle(string $slug)
 * @method static string|null categorySeoDescription(string $slug)
 * @method static string|null categorySeoKeywords(string $slug)
 * @method static \Illuminate\Support\Collection categoryBlogs(string $slug)
 * @method static \Illuminate\Support\Collection categoryPages(string $slug)
 * @method static \Illuminate\Support\Collection categoryChildren(string $slug)
 * @method static \Illuminate\Support\Collection allCategories()
 * @method static \Illuminate\Support\Collection topCategories()
 * @method static \Illuminate\Support\Collection blogComments(string $slug)
 * @method static \Illuminate\Support\Collection pageComments(string $slug)
 * @method static string|null commentContent(int $id)
 * @method static string|null commentAuthor(int $id)
 * @method static void clearCache()
 *
 * @see \App\Services\FiltCMSService
 */
class FiltCMS extends Facade
{
    protected static function getFacadeAccessor()
    {
        // return \EthickS\FiltCMS\FiltCMS::class;
        return 'filtcms';
    }
}
