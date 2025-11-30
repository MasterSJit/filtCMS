{{-- Blog Index Content - This view is included inside the app's layout --}}
<div class="filtcms-blog-list">
    <h1 class="filtcms-blog-list-title">Blog</h1>
    
    @forelse($blogs as $blog)
        <article class="filtcms-blog-item">
            @if($blog->featured_image)
                <div class="filtcms-blog-item-image">
                    <a href="{{ route('filtcms.blog.show', $blog->slug) }}">
                        <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}">
                    </a>
                </div>
            @endif
            
            <div class="filtcms-blog-item-content">
                <h2 class="filtcms-blog-item-title">
                    <a href="{{ route('filtcms.blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                </h2>
                
                <div class="filtcms-blog-item-meta">
                    <span class="filtcms-blog-item-author">By {{ $blog->author->name ?? 'Unknown' }}</span>
                    <span class="filtcms-blog-item-date">{{ $blog->published_at?->format('M d, Y') }}</span>
                    @if($blog->category)
                        <span class="filtcms-blog-item-category">{{ $blog->category->name }}</span>
                    @endif
                </div>
                
                <p class="filtcms-blog-item-excerpt">
                    {{ $blog->excerpt ?? Str::limit(strip_tags($blog->body), 200) }}
                </p>
                
                <a href="{{ route('filtcms.blog.show', $blog->slug) }}" class="filtcms-blog-item-link">
                    Read More →
                </a>
            </div>
        </article>
    @empty
        <p class="filtcms-no-posts">No blog posts found.</p>
    @endforelse
    
    <div class="filtcms-pagination">
        {{ $blogs->links() }}
    </div>
</div>
