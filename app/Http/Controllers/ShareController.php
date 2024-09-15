<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Chest;
use App\Models\Link;
use App\Models\Share;
use App\Models\Story;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function view(Request $request, int $id, string $token)
    {
        $shared = Share::tokenIs($token)
            ->with('post.postable')
            ->findOrFail($id);

        if ($shared->expires_at->lt(now())) {
            $shared->delete();

            $this->flash(__("This shared content has expired"), 'error');
            return redirect()->route('home');
        }

        $post = $shared->post;

        if ($post->postable instanceof Link) {
            return view('link')->with([
                'page_title' => sprintf('%s - #%s', $post->postable->title, $post->postable->hash_id),
                'link' => $post->postable,
                'post' => $post,
            ]);
        }

        if ($post->postable instanceof Chest) {
            return view('chest')->with([
                'page_title' => sprintf('%s - #%s', $post->postable->title, $post->postable->hash_id),
                'chest' => $post->postable,
                'post' => $post,
            ]);
        }

        if ($post->postable instanceof Story) {
            return view('story')->with([
                'page_title' => sprintf('%s', $post->postable->title),
                'story' => $post->postable,
                'post' => $post,
            ]);
        }

        if ($post->postable instanceof Album) {
            return view('album')->with([
                'page_title' => sprintf('%s', $post->postable->title),
                'album' => $post->postable,
                'post' => $post,
            ]);
        }

        return abort(404);
    }
}
