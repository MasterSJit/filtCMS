<?php

namespace EthickS\FiltCMS\Http\Controllers;

use EthickS\FiltCMS\Models\Blog;
use EthickS\FiltCMS\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduledPublishController extends Controller
{
    public function publish(Request $request): JsonResponse
    {
        // Optional: Add security token validation
        $token = $request->header('X-Publish-Token') ?? $request->input('token');
        $expectedToken = config('filtcms.publish_token');

        if ($expectedToken && $token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $now = now();
        $publishedPages = 0;
        $publishedBlogs = 0;

        // Publish scheduled pages
        $pages = Page::where('status', 'scheduled')
            ->where('published_at', '<=', $now)
            ->get();

        foreach ($pages as $page) {
            $page->update(['status' => 'published']);
            $publishedPages++;
        }

        // Publish scheduled blogs
        $blogs = Blog::where('status', 'scheduled')
            ->where('published_at', '<=', $now)
            ->get();

        foreach ($blogs as $blog) {
            $blog->update(['status' => 'published']);
            $publishedBlogs++;
        }

        return response()->json([
            'success' => true,
            'message' => 'Scheduled content published successfully',
            'published' => [
                'pages' => $publishedPages,
                'blogs' => $publishedBlogs,
                'total' => $publishedPages + $publishedBlogs,
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
