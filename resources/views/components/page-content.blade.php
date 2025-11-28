@if($page)
    <article class="filtcms-page">
        <h1>{{ $page->title }}</h1>
        
        @if($page->featured_image)
            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="featured-image">
        @endif
        
        <div class="page-content">
            {!! $page->body !!}
        </div>
    </article>
@else
    <p>Page not found.</p>
@endif
