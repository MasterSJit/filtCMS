@if($blog)
    <article class="filtcms-blog">
        <header>
            <h1>{{ $blog->title }}</h1>
            
            @if($blog->featured_image)
                <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="featured-image">
            @endif
            
            <div class="blog-meta">
                <span class="author">By {{ $blog->author->name ?? 'Unknown' }}</span>
                <span class="date">{{ $blog->published_at->format('M d, Y') }}</span>
                @if($blog->category)
                    <span class="category">{{ $blog->category->name }}</span>
                @endif
            </div>
        </header>
        
        <div class="blog-content">
            {!! $blog->body !!}
        </div>
        
        @if($blog->tags)
            <div class="blog-tags">
                @foreach($blog->tags as $tag)
                    <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </article>
@else
    <p>Blog post not found.</p>
@endif
