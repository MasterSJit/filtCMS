<?php

namespace EthickS\FiltCMS\Http\Controllers;

use EthickS\FiltCMS\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::query()
            ->with(['category', 'author'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // dd($blogs->toArray());
        return view('blogs.index', compact('blogs'));
    }

    public function show(Request $request, string $slug): View
    {
        $blog = Blog::query()
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $blog->recordView(
            $request->ip(),
            $request->userAgent()
        );

        return view('blogs.show', compact('blog'));
    }
}
