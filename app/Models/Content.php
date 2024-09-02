<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Content extends Model
{
    protected $fillable = [
        'content',
        'cover_image',
        'description',
        'published_at',
        'title',
        'slug',
        'status',
        'type',
        'table_of_contents',
    ];

    public const PAGINATION_COUNT = 10;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($content) {
            $content->slug = Str::slug($content->title);
            $content->user_id = auth()->id();
        });
    }
}
