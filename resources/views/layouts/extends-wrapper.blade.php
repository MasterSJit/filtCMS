{{-- 
    This wrapper is for traditional @extends layouts (Blade Only / None starter kit)
    The @extends directive must be at the top level of this file.
--}}
@extends($appLayout)

@section('title', $seoData['title'] ?? '')

@push('meta')
    @if(!empty($seoData['description']))
        <meta name="description" content="{{ $seoData['description'] }}">
    @endif
    @if(!empty($seoData['keywords']))
        <meta name="keywords" content="{{ is_array($seoData['keywords']) ? implode(', ', $seoData['keywords']) : $seoData['keywords'] }}">
    @endif
@endpush

@section($contentSection)
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
@endsection
