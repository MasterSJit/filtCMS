{{-- Blog Show Content - This view is included inside the app's layout --}}
<article class="filtcms-blog-single">
    <header class="filtcms-blog-header">
        <h1 class="filtcms-blog-title">{{ $blog->title }}</h1>
        
        @if($blog->featured_image)
            <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="filtcms-blog-image">
        @endif
        
        <div class="filtcms-blog-meta">
            <span class="filtcms-blog-author">By {{ $blog->author->name ?? 'Unknown' }}</span>
            <span class="filtcms-blog-date">{{ $blog->published_at?->format('M d, Y') }}</span>
            @if($blog->category)
                <span class="filtcms-blog-category">Category: {{ $blog->category->name }}</span>
            @endif
        </div>
        
        <div class="filtcms-blog-stats">
            <span>👁 {{ $blog->views_count ?? 0 }} views</span>
            <span>❤️ {{ $blog->likes_count ?? 0 }} likes</span>
            <span>💬 {{ $blog->approvedComments->count() ?? 0 }} comments</span>
        </div>
        
        @if($blog->tags && count($blog->tags) > 0)
            <div class="filtcms-blog-tags">
                @foreach($blog->tags as $tag)
                    <span class="filtcms-tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </header>
    
    <div class="filtcms-blog-content">
        {!! $blog->body !!}
    </div>
    
    @if($blog->comments_enabled)
        <section class="filtcms-comments">
            <h2>Comments ({{ $blog->approvedComments->count() }})</h2>
            
            @foreach($blog->approvedComments as $comment)
                @include('filtcms::partials.comment', ['comment' => $comment])
            @endforeach
            
            @include('filtcms::partials.comment-form', [
                'commentable_type' => get_class($blog),
                'commentable_id' => $blog->id
            ])
        </section>
    @endif
</article>
