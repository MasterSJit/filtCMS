<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->seo_title ?? $blog->title }}</title>
    <meta name="description" content="{{ $blog->seo_description ?? $blog->excerpt }}">
    <meta name="keywords" content="{{ $blog->seo_keywords }}">
    
    @if(config('filtcms.custom_css'))
        <style>{!! \EthickS\FiltCMS\Models\Setting::get('custom_css', '') !!}</style>
    @endif
</head>
<body>
    <article>
        <header>
            <h1>{{ $blog->title }}</h1>
            
            @if($blog->featured_image)
                <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}">
            @endif
            
            <div class="meta">
                <span>By {{ $blog->author->name ?? 'Unknown' }}</span>
                <span>{{ $blog->published_at->format('M d, Y') }}</span>
                @if($blog->category)
                    <span>Category: {{ $blog->category->name }}</span>
                @endif
            </div>
            
            <div class="stats">
                <span>👁 {{ $blog->views_count }} views</span>
                <span>❤️ {{ $blog->likes_count }} likes</span>
                <span>💬 {{ $blog->comments->count() }} comments</span>
            </div>
            
            @if($blog->tags)
                <div class="tags">
                    @foreach($blog->tags as $tag)
                        <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
        </header>
        
        <div class="content">
            {!! $blog->body !!}
        </div>
        
        @if($blog->comments_enabled)
            <section class="comments">
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
    
    @if(config('filtcms.custom_js'))
        <script>{!! \EthickS\FiltCMS\Models\Setting::get('custom_js', '') !!}</script>
    @endif
</body>
</html>
