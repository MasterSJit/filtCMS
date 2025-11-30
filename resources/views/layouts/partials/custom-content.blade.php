{{-- Custom CSS --}}
@if(!empty($customCss))
    <style>{!! $customCss !!}</style>
@endif

{{-- Global Custom CSS from Settings --}}
@if($globalCss = \EthickS\FiltCMS\Models\Setting::get('custom_css'))
    <style>{!! $globalCss !!}</style>
@endif

{{-- Render Custom HTML --}}
<div class="filtcms-custom-content">
    @if(!empty($renderedHtml))
        {!! $renderedHtml !!}
    @else
        {{-- Fallback content if no custom HTML --}}
        @if(isset($blog))
            <article class="filtcms-blog-single">
                <h1>{{ $blog->title }}</h1>
                @if($blog->featured_image)
                    <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}">
                @endif
                <div class="meta">
                    <span>By {{ $blog->author->name ?? 'Unknown' }}</span>
                    <span>{{ $blog->published_at?->format('M d, Y') }}</span>
                </div>
                <div class="content">{!! $blog->body !!}</div>
            </article>
        @elseif(isset($page))
            <article class="filtcms-page">
                <h1>{{ $page->title }}</h1>
                <div class="content">{!! $page->body !!}</div>
            </article>
        @elseif(isset($blogs))
            <div class="filtcms-blog-list">
                @forelse($blogs as $blogItem)
                    <article>
                        <h2><a href="{{ route('filtcms.blog.show', $blogItem->slug) }}">{{ $blogItem->title }}</a></h2>
                        @if($blogItem->featured_image)
                            <img src="{{ Storage::url($blogItem->featured_image) }}" alt="{{ $blogItem->title }}">
                        @endif
                        <p>{{ $blogItem->excerpt ?? Str::limit(strip_tags($blogItem->body), 200) }}</p>
                    </article>
                @empty
                    <p>No blog posts found.</p>
                @endforelse
                {{ $blogs->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Custom JavaScript --}}
@if(!empty($customJs))
    <script>{!! $customJs !!}</script>
@endif

{{-- Global Custom JS from Settings --}}
@if($globalJs = \EthickS\FiltCMS\Models\Setting::get('custom_js'))
    <script>{!! $globalJs !!}</script>
@endif
