<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContentCollection;
use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index(Request $request): ContentCollection
    {
        $type = $request->get('type');

        $contentsQuery = Content::query()
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

    public function show(string $id)
    {
        //
    }
}
