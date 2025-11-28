<?php

namespace EthickS\FiltCMS\Http\Controllers\Api;

use EthickS\FiltCMS\Http\Controllers\Controller;
use EthickS\FiltCMS\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Blog::query()->published();

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by trending
        if ($request->boolean('trending')) {
            $query->where('is_trending', true);
        }

        // Filter by featured
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $perPage = min($request->input('per_page', 15), 100);

        $blogs = $query->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $blogs->items(),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $blog = Blog::query()
            ->where('slug', $slug)
            ->published()
            ->with(['category', 'author'])
            ->first();

        if (! $blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $blog,
        ]);
    }
}
