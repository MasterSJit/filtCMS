<?php

namespace EthickS\FiltCMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'filtcms_comments';

    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'content',
        'author_name',
        'author_email',
        'user_id',
        'parent_id',
        'status',
        'is_flagged',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_flagged' => 'boolean',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved', 'is_flagged' => false]);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    public function markAsSpam(): void
    {
        $this->update(['status' => 'spam', 'is_flagged' => true]);
    }

    public function containsProfanity(): bool
    {
        $profanityWords = config('filtcms.profanity_words', [
            'spam', 'viagra', 'casino', // Add more words
        ]);

        $content = strtolower($this->content);

        foreach ($profanityWords as $word) {
            if (str_contains($content, strtolower($word))) {
                return true;
            }
        }

        return false;
    }
}
