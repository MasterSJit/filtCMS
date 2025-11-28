<div class="comment" id="comment-{{ $comment->id }}">
    <div class="comment-author">
        <strong>{{ $comment->user ? $comment->user->name : $comment->author_name }}</strong>
        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
    </div>
    
    <div class="comment-content">
        {{ $comment->content }}
    </div>
    
    @if($comment->replies->count() > 0)
        <div class="comment-replies">
            @foreach($comment->replies as $reply)
                @include('filtcms::partials.comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>
