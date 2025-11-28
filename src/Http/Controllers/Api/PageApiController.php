<?php

namespace EthickS\FiltCMS\Http\Controllers\Api;

use EthickS\FiltCMS\Http\Controllers\Controller;
use EthickS\FiltCMS\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Page::query()->published();

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
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

        $pages = $query->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pages->items(),
            'meta' => [
                'current_page' => $pages->currentPage(),
                'last_page' => $pages->lastPage(),
                'per_page' => $pages->perPage(),
                'total' => $pages->total(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->published()
            ->with(['category', 'user'])
            ->first();

        if (! $page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }
}
