<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->seo_title ?? $page->title }}</title>
    <meta name="description" content="{{ $page->seo_description ?? $page->excerpt }}">
    <meta name="keywords" content="{{ $page->seo_keywords }}">
    
    @if(config('filtcms.custom_css'))
        <style>{!! \EthickS\FiltCMS\Models\Setting::get('custom_css', '') !!}</style>
    @endif
</head>
<body>
    <article>
        <header>
            <h1>{{ $page->title }}</h1>
            
            @if($page->featured_image)
                <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}">
            @endif
        </header>
        
        <div class="content">
            {!! $page->body !!}
        </div>
        
        @if($page->comments_enabled)
            <section class="comments">
                <h2>Comments ({{ $page->approvedComments->count() }})</h2>
                
                @foreach($page->approvedComments as $comment)
                    @include('filtcms::partials.comment', ['comment' => $comment])
                @endforeach
                
                @include('filtcms::partials.comment-form', [
                    'commentable_type' => get_class($page),
                    'commentable_id' => $page->id
                ])
            </section>
        @endif
    </article>
    
    @if(config('filtcms.custom_js'))
        <script>{!! \EthickS\FiltCMS\Models\Setting::get('custom_js', '') !!}</script>
    @endif
</body>
</html>
