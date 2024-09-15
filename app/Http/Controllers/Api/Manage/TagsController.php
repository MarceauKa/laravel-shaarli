<?php

namespace App\Http\Controllers\Api\Manage;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware('demo')->except('all');
    }

    public function all()
    {
        $tags = Tag::withCount('posts')
            ->orderByDesc('posts_count')
            ->get();

        return response()->json($tags);
    }

    public function move(Request $request, string $from, string $to)
    {
        $posts = Post::withAnyTags($from)->get();

        $posts->each(function ($item) use ($to) {
            $item->attachTag($to);
        });

        return $this->delete($request, $from);
    }

    public function rename(Request $request, string $from, string $to)
    {
        $fromTag = Tag::named($from)->firstOrFail();
        $toTag = Tag::named($to)->first();

        if ($toTag) {
            response()->json([], 422);
        }

        $fromTag->name = $to;
        $fromTag->save();

        return response()->json();
    }

    public function delete(Request $request, string $tag)
    {
        $tag = Tag::findNamedOrCreate($tag);
        $tag->delete();

        return response()->json();
    }
}
