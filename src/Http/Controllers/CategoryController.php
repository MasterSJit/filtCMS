<?php

namespace EthickS\FiltCMS\Http\Controllers;

use EthickS\FiltCMS\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->whereNull('parent_id') // Only top-level categories
            ->withCount(['pages', 'blogs'])
            ->orderBy('order', 'asc')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show(string $slug): View
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->with(['children', 'pages', 'blogs'])
            ->withCount(['pages', 'blogs'])
            ->firstOrFail();

        return view('categories.show', compact('category'));
    }
}
