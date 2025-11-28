<div class="comment-form">
    <h3>Leave a Comment</h3>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('filtcms.comments.store') }}">
        @csrf
        
        <input type="hidden" name="commentable_type" value="{{ $commentable_type }}">
        <input type="hidden" name="commentable_id" value="{{ $commentable_id }}">
        <input type="hidden" name="parent_id" value="{{ $parent_id ?? null }}">
        
        @guest
            <div class="form-group">
                <label for="author_name">Name *</label>
                <input type="text" id="author_name" name="author_name" value="{{ old('author_name') }}" required>
            </div>
            
            <div class="form-group">
                <label for="author_email">Email *</label>
                <input type="email" id="author_email" name="author_email" value="{{ old('author_email') }}" required>
            </div>
        @endguest
        
        <div class="form-group">
            <label for="content">Comment *</label>
            <textarea id="content" name="content" rows="4" required>{{ old('content') }}</textarea>
        </div>
        
        <button type="submit">Submit Comment</button>
    </form>
</div>
