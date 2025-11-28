<?php

namespace EthickS\FiltCMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'filtcms_pages';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'featured_image',
        'category_id',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'views_count',
        'likes_count',
        'comments_enabled',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'comments_enabled' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            if (empty($page->published_at) && $page->status === 'published') {
                $page->published_at = now();
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            if ($page->isDirty('status') && $page->status === 'published' && empty($page->published_at)) {
                $page->published_at = now();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function approvedComments(): MorphMany
    {
        return $this->comments()->where('status', 'approved');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->where('published_at', '>', now());
    }

    public function getUrlAttribute(): string
    {
        $path = [];

        if ($this->category) {
            $path[] = $this->category->full_path;
        }

        $path[] = $this->slug;

        return '/' . implode('/', $path);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(PageView::class);
    }

    public function recordView(string $ipAddress, ?string $userAgent = null): bool
    {
        $recentView = $this->pageViews()
            ->where('ip_address', $ipAddress)
            ->where('viewed_at', '>', now()->subHours(24))
            ->exists();

        if (! $recentView) {
            $this->pageViews()->create([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'viewed_at' => now(),
            ]);

            $this->increment('views_count');

            return true;
        }

        return false;
    }
}
