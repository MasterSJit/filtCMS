<?php

namespace EthickS\FiltCMS\View\Components;

use Illuminate\View\Component;
use EthickS\FiltCMS\Models\Blog;

class LatestBlogs extends Component
{
    public $blogs;
    public $limit;

    public function __construct(int $limit = 5)
    {
        $this->limit = $limit;
        $this->blogs = Blog::with(['category', 'author'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function render()
    {
        return view('filtcms::components.latest-blogs');
    }
}
