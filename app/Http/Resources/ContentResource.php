<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentResource extends JsonResource
{
    protected bool $truncateContent;

    public function __construct($resource, $truncateContent = false)
    {
        parent::__construct($resource);
        $this->truncateContent = $truncateContent;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => Str::ucfirst($this->type),
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->truncateContent
                ? Str::limit(strip_tags($this->content), 115)
                : $this->replaceRelativePathsWithAbsolute($this->content),
            'table_of_contents' => $this->table_of_contents,
            'cover_image' => sprintf("%s/%s", config('app.url'), Storage::url($this->cover_image)),
            'url' => sprintf("%s/%s/%s", config('app.url'), Str::plural($this->type), $this->slug),
            'published_at' => Carbon::parse($this->published_at)->format('F j, Y'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function replaceRelativePathsWithAbsolute(string $content): string
    {
        return Str::replace('src="/', 'src="' . config('app.url') . '/', $content);
    }
}
