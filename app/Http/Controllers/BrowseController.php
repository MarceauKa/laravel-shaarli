<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Chest;
use App\Models\Link;
use App\Models\Post;
use App\Services\Shaark\Shaark;
use App\Models\Story;
use App\Models\Tag;
use App\Models\Wall;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function wall(Request $request, Shaark $shaark, string $wall = null)
    {
        /** @var Wall $wall */
        $wall = Wall::slugIs($wall)
            ->withPrivate($request)
            ->firstOrFail();

        $walls = Wall::withPrivate($request)
                ->where('id', '!=', $wall->id)
                ->get();

        $posts = Post::with('tags', 'postable')
            ->withPrivate($request)
            ->withWallRestrictions($wall->restrict_tags, $wall->restrict_cards)
            ->latest(sprintf('%s_at', $shaark->getPostsOrder()))
            ->simplePaginate(20);

        $tags = collect([]);

        if ($wall->appearance['show_tags']) {
            $tags = Tag::withPostsFor($request)
                ->orderBy('posts_count', 'desc')
                ->get();
        }

        return view('home')->with([
            'page_title' => sprintf('%s - %s', $wall->title, app('shaark')->getName()),
            'walls' => $walls,
            'wall' => $wall,
            'posts' => $posts,
            'tags' => $tags,
            'compact' => $wall->appearance['compact'],
            'columns' => $this->getColumnsConfigFor($wall),
        ]);
    }

    public function link(Request $request, string $hash)
    {
        $link = Link::withPrivate($request)
            ->with('post.tags')
            ->hashIdIs($hash)
            ->firstOrFail();

        return view('link')->with([
            'page_title' => sprintf('%s - #%s', $link->title, $link->hash_id),
            'link' => $link,
            'post' => $link->post,
        ]);
    }

    public function story(Request $request, string $slug)
    {
        $story = Story::withPrivate($request)
            ->with('post.tags')
            ->slugIs($slug)
            ->firstOrFail();

        return view('story')->with([
            'page_title' => sprintf('%s', $story->title),
            'story' => $story,
            'post' => $story->post,
        ]);
    }

    public function chest(Request $request, string $hash)
    {
        $chest = Chest::withPrivate($request)
            ->with('post.tags')
            ->hashIdIs($hash)
            ->firstOrFail();

        return view('chest')->with([
            'page_title' => sprintf('%s - #%s', $chest->title, $chest->hash_id),
            'chest' => $chest,
            'post' => $chest->post,
        ]);
    }

    public function album(Request $request, string $hash)
    {
        $album = Album::withPrivate($request)
            ->with('post.tags')
            ->hashIdIs($hash)
            ->firstOrFail();

        return view('album')->with([
            'page_title' => sprintf('%s', $album->title),
            'album' => $album,
            'post' => $album->post,
        ]);
    }

    public function tag(Request $request, Shaark $shaark, string $tag)
    {
        $tag = Tag::named($tag)
            ->withCount('posts')
            ->firstOrFail();

        $posts = Post::withPrivate($request)
            ->pinnedFirst()
            ->with('postable', 'tags')
            ->withAllTags($tag)
            ->latest(sprintf('%s_at', $shaark->getPostsOrder()))
            ->simplePaginate(20);

        abort_if($posts->isEmpty(), 404);

        return view('tag')->with([
            'page_title' => vsprintf('%s %s - %s n°%d', [
                __('Tagged'),
                $tag->name,
                __('Page'),
                $request->input('page', 1),
            ]),
            'tag' => $tag,
            'posts' => $posts,
        ]);
    }

    protected function getColumnsConfigFor(Wall $wall)
    {
        $default = $wall->appearance['columns'];

        switch ($default) {
            case 4:
                return json_encode(['default' => 4, '1000' => 3, '800' => 2, '450' => 1]);
                break;
            case 3:
                return json_encode(['default' => 3, '800' => 2, '450' => 1]);
                break;
            case 2:
                return json_encode(['default' => 2, '450' => 1]);
                break;
            case 1:
            default:
                return json_encode(['default' => 1]);
                break;
        }
    }
}
