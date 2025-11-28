<?php

namespace EthickS\FiltCMS\View\Components;

use EthickS\FiltCMS\Models\Page;
use Illuminate\View\Component;

class PageContent extends Component
{
    public $page;

    public function __construct(string $slug)
    {
        $this->page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->first();
    }

    public function render()
    {
        return view('filtcms::components.page-content');
    }
}
