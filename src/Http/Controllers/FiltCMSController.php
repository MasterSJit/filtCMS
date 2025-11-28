<?php

namespace EthickS\FiltCMS\Http\Controllers;

use EthickS\FiltCMS\Models\Blog;
use EthickS\FiltCMS\Models\Page;
use EthickS\FiltCMS\Models\Category;
use EthickS\FiltCMS\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FiltCMSController extends Controller
{
    public function showBlog(Request $request, ...$segments)
    {
        $slug = array_pop($segments);
        $blog = Blog::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $blog->incrementViews();

        return view('filtcms::blog.show', compact('blog'));
    }

    public function showPage(Request $request, ...$segments)
    {
        $slug = array_pop($segments);
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $page->incrementViews();

        return view('filtcms::page.show', compact('page'));
    }

    public function blogIndex(Request $request)
    {
        $perPage = Setting::get('posts_per_page', 10);
        
        $blogs = Blog::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return view('filtcms::blog.index', compact('blogs'));
    }

    public function categoryBlog(Request $request, ...$segments)
    {
        $categorySlug = end($segments);
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        $perPage = Setting::get('posts_per_page', 10);
        
        $blogs = Blog::with(['category', 'author'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return view('filtcms::blog.category', compact('blogs', 'category'));
    }
}
