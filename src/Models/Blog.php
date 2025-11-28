<?php

namespace EthickS\FiltCMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'filtcms_blogs';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'excerpt',
        'featured_image',
        'category_id',
        'status',
        'published_at',
        'is_trending',
        'is_featured',
        'comments_enabled',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'views_count',
        'likes_count',
        'user_id',
        'tags',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_trending' => 'boolean',
        'is_featured' => 'boolean',
        'comments_enabled' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'tags' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            if (empty($blog->published_at) && $blog->status === 'published') {
                $blog->published_at = now();
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('title') && empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
            if ($blog->isDirty('status') && $blog->status === 'published' && empty($blog->published_at)) {
                $blog->published_at = now();
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
        return $query->where('status', 'published');
        // ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
        // ->where('published_at', '>', now());
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getUrlAttribute(): string
    {
        $path = ['blog'];

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

    public function blogViews(): HasMany
    {
        return $this->hasMany(BlogView::class);
    }

    public function recordView(string $ipAddress, ?string $userAgent = null): bool
    {
        $recentView = $this->blogViews()
            ->where('ip_address', $ipAddress)
            ->where('viewed_at', '>', now()->subHours(24))
            ->exists();

        if (! $recentView) {
            $this->blogViews()->create([
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
