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

    protected $casts = [
        'content' => 'array',
        'published_at' => 'datetime',
        'table_of_contents' => 'array',
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
            $content->table_of_contents = rescue(fn () => tiptap_converter()->asTOC($content->content));
        });

        static::updating(function ($content) {
            $content->content = self::appendSlugIdsToHeadings($content->content);
            $content->slug = Str::slug($content->title);
            $content->table_of_contents = rescue(fn () => tiptap_converter()->asTOC($content->content));
        });
    }

    public static function appendSlugIdsToHeadings(array $content): array
    {
        $process = function ($node) use (&$process) {
            if (isset($node['type']) && $node['type'] === 'heading') {
                $headingText = implode('', array_map(fn($text) => $text['text'], $node['content']));
                $newId = Str::slug($headingText);
                $node['attrs']['id'] = $newId;
            }

            if (isset($node['content'])) {
                foreach ($node['content'] as &$child) {
                    $process($child);
                }
            }

            return $node;
        };

        foreach ($content as &$node) {
            $process($node);
        }

        return $content;
    }
}
