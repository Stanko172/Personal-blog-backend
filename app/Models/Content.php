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
            $content->content = self::appendSlugIdsToHeadings($content->content);
            $content->slug = Str::slug($content->title);
            $content->user_id = auth()->id();
            $content->table_of_contents = rescue(tiptap_converter()->asTOC($content->content));
        });

        static::updating(function ($content) {
            $content->content = self::appendSlugIdsToHeadings($content->content);
            $content->slug = Str::slug($content->title);
            $content->table_of_contents = rescue(tiptap_converter()->asTOC($content->content));
        });
    }

    public static function appendSlugIdsToHeadings(string $content): string
    {
        $pattern = '/<h([1-6])(?:\s+id="([^"]*)")?>(.*?)<\/h\1>/';

        $replacement = function ($matches) {
            $tag = $matches[1];
            $headingText = $matches[3];

            $newId = Str::slug($headingText);

            return "<h{$tag} id=\"{$newId}\">{$headingText}</h{$tag}>";
        };

        return preg_replace_callback($pattern, $replacement, $content);
    }

}
