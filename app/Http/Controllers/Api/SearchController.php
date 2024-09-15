<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Services\ModelSearch;
use App\Services\Shaark\Shaark;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request, Shaark $shaark)
    {
        $query = $request->get('query');
        $default_search = $shaark->getUseDefaultSearch();

        if (mb_strlen($query) < 3) {
            abort(422);
        }

        $tags = (new ModelSearch(Tag::class, ['name']))
            ->useDefaultSearch($default_search)
            ->search($query, 5);


        $posts = (new ModelSearch(Post::class, ['postable' => ['title', 'content']]))
            ->useDefaultSearch($default_search)
            ->withCallback(function ($query) {
                return $query->with('tags', 'postable')
                    ->withPrivate(auth('api')->user());
            })
            ->search($query, 10);

        return [
            'posts' => PostResource::collection($posts),
            'tags' => TagResource::collection($tags),
        ];
    }
}
