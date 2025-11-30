{{--
    This wrapper view uses the user's app layout and renders custom HTML/CSS/JS.
    The custom HTML is rendered through Blade engine so all Blade directives work.
--}}
@php
    use Illuminate\Support\Facades\Blade;
    
    $layoutType = \EthickS\FiltCMS\Models\Setting::get('app_layout_type', 'component');
    $configuredLayout = $appLayout ?? \EthickS\FiltCMS\Models\Setting::get('app_layout', '');
    
    // Use plugin's default layout if no layout is configured
    $useDefaultLayout = empty($configuredLayout);
    if ($useDefaultLayout) {
        $appLayout = 'filtcms::layouts.default';
    } else {
        $appLayout = $configuredLayout;
    }
    
    // Prepare data for Blade rendering
    $bladeData = [
        'blog' => $blog ?? null,
        'blogs' => $blogs ?? null,
        'page' => $page ?? null,
        'seoData' => $seoData ?? [],
    ];
    
    // Render custom HTML through Blade engine - this makes {{ }}, @foreach, etc. work
    $renderedHtml = '';
    if (!empty($customHtml)) {
        try {
            $renderedHtml = Blade::render($customHtml, $bladeData);
        } catch (\Throwable $e) {
            $renderedHtml = '<div class="text-red-500">Error rendering custom HTML: ' . e($e->getMessage()) . '</div>';
        }
    }
    
    // Render custom CSS through Blade (in case they use variables)
    $renderedCss = '';
    if (!empty($customCss)) {
        try {
            $renderedCss = Blade::render($customCss, $bladeData);
        } catch (\Throwable $e) {
            $renderedCss = '/* Error rendering CSS: ' . e($e->getMessage()) . ' */';
        }
    }
    
    // Render custom JS through Blade (in case they use variables)
    $renderedJs = '';
    if (!empty($customJs)) {
        try {
            $renderedJs = Blade::render($customJs, $bladeData);
        } catch (\Throwable $e) {
            $renderedJs = '/* Error rendering JS: ' . e($e->getMessage()) . ' */';
        }
    }
@endphp

{{-- Always use component-based rendering for flexibility --}}
@if($useDefaultLayout || $layoutType === 'component')
    {{-- Component-based layout (Livewire/Flux or FiltCMS default) --}}
    <x-dynamic-component :component="$appLayout" :title="$seoData['title'] ?? null">
        @push('meta')
            @if(!empty($seoData['description']))
                <meta name="description" content="{{ $seoData['description'] }}">
            @endif
            @if(!empty($seoData['keywords']))
                <meta name="keywords" content="{{ $seoData['keywords'] }}">
            @endif
        @endpush

        {{-- Custom CSS --}}
        @if($renderedCss)
            <style>{!! $renderedCss !!}</style>
        @endif

        {{-- Rendered HTML content --}}
        {!! $renderedHtml !!}

        {{-- Custom JS --}}
        @if($renderedJs)
            <script>{!! $renderedJs !!}</script>
        @endif
    </x-dynamic-component>
@else
    {{-- Blade Only (None) - Use a separate extends wrapper --}}
    @include('filtcms::layouts.custom-extends-wrapper', [
        'appLayout' => $appLayout,
        'contentSection' => $contentSection ?? 'content',
        'seoData' => $seoData ?? [],
        'renderedHtml' => $renderedHtml,
        'renderedCss' => $renderedCss,
        'renderedJs' => $renderedJs,
    ])
@endif
