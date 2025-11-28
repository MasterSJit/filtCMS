<?php

namespace EthickS\FiltCMS\View\Components;

use EthickS\FiltCMS\Models\Blog;
use Illuminate\View\Component;

class BlogContent extends Component
{
    public $blog;

    public function __construct(string $slug)
    {
        $this->blog = Blog::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->first();
    }

    public function render()
    {
        return view('filtcms::components.blog-content');
    }
}
