<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Content extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'type',
        'status',
        'content',
        'table_of_contents',
    ];

    protected $casts = [
        'table_of_contents' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($content) {
            $content->slug = Str::slug($content->title);
            $content->user_id = auth()->id();
        });
    }
}
