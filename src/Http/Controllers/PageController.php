<?php

namespace EthickS\FiltCMS\Http\Controllers;

use EthickS\FiltCMS\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $page->recordView(
            $request->ip(),
            $request->userAgent()
        );

        return view('pages.show', compact('page'));
    }
}
