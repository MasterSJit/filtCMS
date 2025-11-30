<?php

namespace EthickS\FiltCMS\Http\Controllers;

use EthickS\FiltCMS\Models\Page;
use EthickS\FiltCMS\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(Request $request, string $slug): View|Response
    {
        $page = Page::query()
            ->with(['approvedComments'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $page->recordView(
            $request->ip(),
            $request->userAgent()
        );

        $viewType = Setting::get('page_show_view_type', 'default');

        return match ($viewType) {
            'file' => $this->renderFileView(
                Setting::get('page_show_view_file', 'page.show'),
                compact('page')
            ),
            'custom' => $this->renderCustomView(compact('page')),
            default => $this->renderDefaultView('filtcms::page.show', compact('page'), $this->getPageSeoData($page)),
        };
    }

    /**
     * Render using the app's layout with plugin content
     * 
     * @return View|\Inertia\Response
     */
    protected function renderDefaultView(string $pluginView, array $data, array $seoData = []): mixed
    {
        $layoutType = Setting::get('app_layout_type', 'component');
        $layout = Setting::get('app_layout', 'layouts.app');
        $section = Setting::get('app_layout_section', 'content');

        // Handle Inertia (React/Vue) - return JSON response
        if ($layoutType === 'inertia' && class_exists(\Inertia\Inertia::class)) {
            $component = Setting::get('app_inertia_component', 'FiltCMS/Page');
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

        return view('filtcms::page.show', $data);
    }

    /**
     * Render custom HTML/CSS/JS content directly (no layout wrapper)
     */
    protected function renderCustomView(array $data): Response
    {
        $html = Setting::get('page_show_custom_html', '');
        $css = Setting::get('page_show_custom_css', '');
        $js = Setting::get('page_show_custom_js', '');

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
     * Get SEO data for page
     */
    protected function getPageSeoData(Page $page): array
    {
        return [
            'title' => $page->seo_title ?? $page->title,
            'description' => $page->seo_description ?? $page->excerpt ?? '',
            'keywords' => $page->seo_keywords ?? '',
        ];
    }
}
