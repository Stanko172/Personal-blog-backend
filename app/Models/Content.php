<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
