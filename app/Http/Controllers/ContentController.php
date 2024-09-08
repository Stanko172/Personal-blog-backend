<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContentCollection;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index(Request $request): ContentCollection
    {
        $type = $request->get('type');

        $contentsQuery = Content::query()
            ->where('status', 'published')
            ->orderBy('created_at', 'desc');

        if (!isset($type)) {
            $contents = $contentsQuery
                ->paginate(Content::PAGINATION_COUNT);

            return ContentCollection::make($contents);
        }

        $contents = $contentsQuery
            ->where('type', $type)
            ->paginate(Content::PAGINATION_COUNT);

        return ContentCollection::make($contents);
    }

    public function show(string $slug): ContentResource
    {
        $content = Content::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return ContentResource::make($content);
    }
}
