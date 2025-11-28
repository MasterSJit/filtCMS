<div class="filtcms-latest-blogs">
    <h2>Latest Blog Posts</h2>
    
    @if($blogs->count() > 0)
        <div class="blog-list">
            @foreach($blogs as $blog)
                <article class="blog-item">
                    @if($blog->featured_image)
                        <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}">
                    @endif
                    
                    <h3>
                        <a href="{{ $blog->url }}">{{ $blog->title }}</a>
                    </h3>
                    
                    @if($blog->excerpt)
                        <p>{{ $blog->excerpt }}</p>
                    @endif
                    
                    <div class="blog-meta">
                        <span>{{ $blog->published_at->format('M d, Y') }}</span>
                        @if($blog->category)
                            <span>{{ $blog->category->name }}</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <p>No blog posts available.</p>
    @endif
</div>
