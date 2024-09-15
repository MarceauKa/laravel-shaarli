<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChestRequest;
use App\Models\Chest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class ChestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(StoreChestRequest $request)
    {
        $data = collect($request->validated());

        /** @var Chest $link */
        $chest = Chest::create($data->only([
            'title',
            'content',
        ])->toArray());

        $post = new Post(['is_private' => true, 'user_id' => $request->user()->id]);
        $post->is_pinned = $data;
        $post->postable()->associate($chest)->save();

        if ($data['tags']) {
            $post->syncTags($data['tags']);
        }

        $post->save();

        return response()->json([
            'post' => new PostResource($post),
            'status' => 'created',
        ]);
    }

    public function update(StoreChestRequest $request, int $id)
    {
        $chest = Chest::findOrFail($id);
        $data = collect($request->validated());

        $chest->fill($data->only('title', 'content')->toArray());
        $chest->post->is_pinned = $data->get('is_pinned', $chest->post->is_pinned);

        if ($data['tags']) {
            $chest->post->syncTags($data['tags']);
        }

        $chest->save();
        $chest->post->save();

        return response()->json([
            'post' => new PostResource($chest->post),
            'status' => 'updated',
        ]);
    }

    public function delete(Request $request, int $id)
    {
        /** @var Chest $link */
        $chest = Chest::with('post')->findOrFail($id);

        $chest->post->delete();
        $chest->delete();

        return response()->json([
            'id' => $chest->id,
            'status' => 'deleted',
        ]);
    }
}
