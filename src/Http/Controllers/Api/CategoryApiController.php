<?php

namespace EthickS\FiltCMS\Http\Controllers\Api;

use EthickS\FiltCMS\Http\Controllers\Controller;
use EthickS\FiltCMS\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Category::query();

        // Filter top-level categories only
        if ($request->boolean('top_only')) {
            $query->whereNull('parent_id');
        }

        // Filter by parent
        if ($request->has('parent')) {
            $query->whereHas('parent', function ($q) use ($request) {
                $q->where('slug', $request->parent);
            });
        }

        // Include counts
        if ($request->boolean('with_counts')) {
            $query->withCount(['pages', 'blogs']);
        }

        $categories = $query->orderBy('order', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function show(string $slug, Request $request): JsonResponse
    {
        $query = Category::query()->where('slug', $slug);

        // Include relationships
        if ($request->boolean('with_children')) {
            $query->with('children');
        }

        if ($request->boolean('with_blogs')) {
            $query->with(['blogs' => function ($q) {
                $q->published()->orderBy('published_at', 'desc');
            }]);
        }

        if ($request->boolean('with_pages')) {
            $query->with(['pages' => function ($q) {
                $q->published()->orderBy('published_at', 'desc');
            }]);
        }

        if ($request->boolean('with_counts')) {
            $query->withCount(['pages', 'blogs']);
        }

        $category = $query->first();

        if (! $category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }
}
