{{--
    This wrapper view uses the user's app layout and injects FiltCMS content.
    Supports all Laravel starter kit layouts:
    - Blade Only (None): Traditional @extends layout
    - Livewire/Flux: Component-based layout with {{ $slot }}
    - Inertia (React/Vue): Not supported here (requires Inertia response)
--}}
@php
    $layoutType = \EthickS\FiltCMS\Models\Setting::get('app_layout_type', 'component');
    $configuredLayout = $appLayout ?? \EthickS\FiltCMS\Models\Setting::get('app_layout', '');
    
    // Use plugin's default layout component if no layout is configured
    $useDefaultLayout = empty($configuredLayout);
    if ($useDefaultLayout) {
        // Point to the component at views/components/layouts/default.blade.php
        $appLayout = 'filtcms::layouts.default';
    } else {
        $appLayout = $configuredLayout;
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
                <meta name="keywords" content="{{ is_array($seoData['keywords']) ? implode(', ', $seoData['keywords']) : $seoData['keywords'] }}">
            @endif
        @endpush

        {{-- Inject custom CSS from global settings --}}
        @if($globalCss = \EthickS\FiltCMS\Models\Setting::get('custom_css'))
            <style>{!! $globalCss !!}</style>
        @endif

        {{-- Render the plugin's content view --}}
        @include($contentView)

        {{-- Inject custom JS from global settings --}}
        @if($globalJs = \EthickS\FiltCMS\Models\Setting::get('custom_js'))
            <script>{!! $globalJs !!}</script>
        @endif
    </x-dynamic-component>
@else
    {{-- Blade Only (None) - Use a separate extends wrapper --}}
    @include('filtcms::layouts.extends-wrapper', [
        'appLayout' => $appLayout,
        'contentSection' => $contentSection ?? 'content',
        'contentView' => $contentView,
        'seoData' => $seoData ?? [],
    ])
@endif
