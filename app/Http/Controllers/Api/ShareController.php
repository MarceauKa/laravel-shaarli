<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShareResource;
use App\Models\Post;
use App\Models\Share;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request, int $post_id)
    {
        Share::clearExpired();
        $shares = Share::postIs($post_id)->get();

        return response()->json([
            'shares' => ShareResource::collection($shares),
        ]);
    }

    public function store(Request $request, int $post_id)
    {
        $post = Post::findOrFail($post_id);

        $validated = $this->validate($request, [
            'expiration' => [
                'required',
                'in:hour,hours,day,days,week,weeks,month',
            ]
        ]);

        $share = new Share(['post_id' => $post->id]);
        $share->setExpiration($validated['expiration']);
        $share->generateToken();

        if ($share->save()) {
            return response()->json([
                'share' => new ShareResource($share),
                'status' => 'created',
            ]);
        }

        return response()->json([
            'message' => __('Unable to create link for this content'),
            'status' => 'error',
        ]);
    }
}
