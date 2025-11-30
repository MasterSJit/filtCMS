<?php

namespace EthickS\FiltCMS\Http\Controllers;

use EthickS\FiltCMS\Models\Blog;
use EthickS\FiltCMS\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View|Response
    {
        $blogs = Blog::query()
            ->with(['category', 'author'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(Setting::get('posts_per_page', 12));

        $viewType = Setting::get('blog_index_view_type', 'default');

        return match ($viewType) {
            'file' => $this->renderFileView(
                Setting::get('blog_index_view_file', 'blog.index'),
                compact('blogs')
            ),
            'custom' => $this->renderCustomView('blog_index', compact('blogs')),
            default => $this->renderDefaultView('filtcms::blog.index', compact('blogs'), $this->getBlogIndexSeoData()),
        };
    }

    public function show(Request $request, string $slug): View|Response
    {
        $blog = Blog::query()
            ->with(['category', 'author', 'approvedComments'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $blog->recordView(
            $request->ip(),
            $request->userAgent()
        );

        $viewType = Setting::get('blog_show_view_type', 'default');

        return match ($viewType) {
            'file' => $this->renderFileView(
                Setting::get('blog_show_view_file', 'blog.show'),
                compact('blog')
            ),
            'custom' => $this->renderCustomView('blog_show', compact('blog')),
            default => $this->renderDefaultView('filtcms::blog.show', compact('blog'), $this->getBlogSeoData($blog)),
        };
    }

    /**
     * Render using the app's layout with plugin content
     * 
     * @return View|\Inertia\Response
     */
    protected function renderDefaultView(string $pluginView, array $data, array $seoData = []): mixed
    {
        $layoutType = Setting::get('app_layout_type', 'livewire');
        $layout = Setting::get('app_layout', 'layouts.app');
        $section = Setting::get('app_layout_section', 'content');

        // Handle Inertia (React/Vue) - return JSON response
        if ($layoutType === 'inertia' && class_exists(\Inertia\Inertia::class)) {
            $component = Setting::get('inertia_page_component', 'FiltCMS/Blog');
            return \Inertia\Inertia::render($component, array_merge($data, [
                'seoData' => $seoData,
            ]));
        }

        // Blade or Livewire - use the wrapper view
        return view('filtcms::layouts.wrapper', array_merge($data, [
            'appLayout' => $layout,
            'contentSection' => $section,
            'contentView' => $pluginView,
            'seoData' => $seoData,
        ]));
    }

    /**
     * Render a custom file view from the user's application
     */
    protected function renderFileView(string $viewFile, array $data): View
    {
        if (view()->exists($viewFile)) {
            return view($viewFile, $data);
        }

        // Fallback to default
        $fallbackView = str_contains($viewFile, 'index') ? 'filtcms::blog.index' : 'filtcms::blog.show';
        return view($fallbackView, $data);
    }

    /**
     * Render custom HTML/CSS/JS content directly (no layout wrapper)
     */
    protected function renderCustomView(string $type, array $data): Response
    {
        $html = Setting::get("{$type}_custom_html", '');
        $css = Setting::get("{$type}_custom_css", '');
        $js = Setting::get("{$type}_custom_js", '');

        // Render HTML through Blade engine so all directives work
        $renderedHtml = '';
        if (!empty($html)) {
            try {
                $renderedHtml = \Illuminate\Support\Facades\Blade::render($html, $data);
            } catch (\Throwable $e) {
                $renderedHtml = '<div style="color:red;">Error rendering HTML: ' . e($e->getMessage()) . '</div>';
            }
        }

        // Render CSS through Blade (in case they use variables)
        $renderedCss = '';
        if (!empty($css)) {
            try {
                $renderedCss = \Illuminate\Support\Facades\Blade::render($css, $data);
            } catch (\Throwable $e) {
                $renderedCss = '/* Error: ' . e($e->getMessage()) . ' */';
            }
        }

        // Render JS through Blade (in case they use variables)
        $renderedJs = '';
        if (!empty($js)) {
            try {
                $renderedJs = \Illuminate\Support\Facades\Blade::render($js, $data);
            } catch (\Throwable $e) {
                $renderedJs = '/* Error: ' . e($e->getMessage()) . ' */';
            }
        }

        // Build the complete HTML response
        $fullHtml = $renderedHtml;
        
        // Inject CSS if provided
        if (!empty($renderedCss)) {
            $styleTag = "<style>{$renderedCss}</style>";
            // If there's a </head>, inject before it, otherwise prepend
            if (stripos($fullHtml, '</head>') !== false) {
                $fullHtml = str_ireplace('</head>', $styleTag . '</head>', $fullHtml);
            } else {
                $fullHtml = $styleTag . $fullHtml;
            }
        }

        // Inject JS if provided
        if (!empty($renderedJs)) {
            $scriptTag = "<script>{$renderedJs}</script>";
            // If there's a </body>, inject before it, otherwise append
            if (stripos($fullHtml, '</body>') !== false) {
                $fullHtml = str_ireplace('</body>', $scriptTag . '</body>', $fullHtml);
            } else {
                $fullHtml .= $scriptTag;
            }
        }

        return response($fullHtml);
    }

    /**
     * Get SEO data for blog index
     */
    protected function getBlogIndexSeoData(): array
    {
        return [
            'title' => Setting::get('default_meta_title', 'Blog'),
            'description' => Setting::get('default_meta_description', ''),
            'keywords' => Setting::get('default_meta_keywords', ''),
        ];
    }

    /**
     * Get SEO data for single blog
     */
    protected function getBlogSeoData(Blog $blog): array
    {
        return [
            'title' => $blog->seo_title ?? $blog->title,
            'description' => $blog->seo_description ?? $blog->excerpt ?? '',
            'keywords' => $blog->seo_keywords ?? '',
        ];
    }
}
