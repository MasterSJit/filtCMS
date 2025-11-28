<?php

namespace EthickS\FiltCMS\Http\Controllers\Api;

use EthickS\FiltCMS\Http\Controllers\Controller;
use EthickS\FiltCMS\Models\Blog;
use EthickS\FiltCMS\Models\Comment;
use EthickS\FiltCMS\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Comment::query()->where('status', 'approved');

        // Filter by type
        if ($request->has('type')) {
            $type = $request->type === 'blog' ? Blog::class : Page::class;
            $query->where('commentable_type', $type);
        }

        // Filter by content slug
        if ($request->has('content_slug')) {
            $slug = $request->content_slug;
            $query->where(function ($q) use ($slug) {
                $q->whereHas('commentable', function ($subQ) use ($slug) {
                    $subQ->where('slug', $slug);
                });
            });
        }

        // Only parent comments (not replies)
        if ($request->boolean('parents_only')) {
            $query->whereNull('parent_id');
        }

        $perPage = min($request->input('per_page', 20), 100);

        $comments = $query->with(['user', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $comment = Comment::query()
            ->where('id', $id)
            ->where('status', 'approved')
            ->with(['user', 'replies', 'parent'])
            ->first();

        if (! $comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $comment,
        ]);
    }
}
