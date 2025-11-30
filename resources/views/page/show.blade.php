{{-- Page Show Content - This view is included inside the app's layout --}}
<article class="filtcms-page">
    <header class="filtcms-page-header">
        <h1 class="filtcms-page-title">{{ $page->title }}</h1>
        
        @if($page->featured_image)
            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="filtcms-page-image">
        @endif
    </header>
    
    <div class="filtcms-page-content">
        {!! $page->body !!}
    </div>
    
    @if($page->comments_enabled)
        <section class="filtcms-comments">
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
