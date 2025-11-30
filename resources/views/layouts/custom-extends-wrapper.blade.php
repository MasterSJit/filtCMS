{{-- 
    This wrapper is for traditional @extends layouts with custom HTML/CSS/JS content.
    The @extends directive must be at the top level of this file.
--}}
@extends($appLayout)

@section('title', $seoData['title'] ?? '')

@push('meta')
    @if(!empty($seoData['description']))
        <meta name="description" content="{{ $seoData['description'] }}">
    @endif
    @if(!empty($seoData['keywords']))
        <meta name="keywords" content="{{ $seoData['keywords'] }}">
    @endif
@endpush

@section($contentSection)
    {{-- Custom CSS --}}
    @if(!empty($renderedCss))
        <style>{!! $renderedCss !!}</style>
    @endif

    {{-- Rendered HTML content (already processed through Blade::render) --}}
    {!! $renderedHtml !!}

    {{-- Custom JS --}}
    @if(!empty($renderedJs))
        <script>{!! $renderedJs !!}</script>
    @endif
@endsection
