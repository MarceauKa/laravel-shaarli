<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use App\Services\Shaark\Shaark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'demo'])->only(['moderate', 'delete']);
        $this->middleware('can:comments.see')->only('get');
        $this->middleware('can:comments.add')->only('store');
    }

    public function get(Request $request, int $id)
    {
        $post = Post::withPrivate($request->user('api'))
            ->findOrFail($id);

        $comments = Comment::postIs($id)
            ->withVisible($request->user('api'))
            ->get();

        return response()->json([
            'comments' => CommentResource::collection($comments),
        ]);
    }

    public function store(AddCommentRequest $request, Shaark $shaark, int $id)
    {
        $data = $request->validated();
        $user = $request->user('api');

        $comment = new Comment([
            'content' => $data['content'],
            'comment_id' => $data['reply'],
            'post_id' => $id,
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? null : $data['name'],
            'user_email' => $user ? null : $data['email'],
        ]);

        if ($user) {
            $comment->is_visible = true;
        } else {
            switch ($shaark->getCommentsModeration()) {
                case 'all':
                case 'whitelist':
                    $whitelisted = Comment::where('user_email', $data['email'])
                        ->isVisible()
                        ->count();
                    $comment->is_visible = $whitelisted > 0;
                    break;
                case 'disabled':
                default:
                    $comment->is_visible = true;
                    break;
            }
        }

        $comment->save();

        return response()->json([
            'comment' => new CommentResource($comment),
            'message' => $comment->is_visible ? __('Saved') : __('Your comment will be displayed once moderated'),
            'status' => 'created',
        ]);
    }

    public function moderate(Request $request, Shaark $shaark, int $id, int $comment_id)
    {
        $post = Post::withPrivate($request->user('api'))
                    ->findOrFail($id);

        $comment = Comment::postIs($id)
                    ->isNotVisible()
                    ->findOrFail($comment_id);

        $comment->is_visible = true;
        $comment->save();

        if ($shaark->getCommentsModeration() === 'whitelist') {
            DB::table('comments')
                ->where('is_visible', 0)
                ->where('user_email', $comment->user_email)
                ->update([
                    'is_visible' => true,
                ]);

            return response()->json([
                'status' => 'moderated',
                'message' => __('This comment and all others comments from this user are now visible'),
            ]);
        }

        return response()->json([
            'status' => 'moderated',
            'message' => __('This comment is now visible'),
        ]);
    }

    public function delete(Request $request, int $id, int $comment_id)
    {
        $post = Post::withPrivate($request->user('api'))
            ->findOrFail($id);

        $comment = Comment::postIs($id)
            ->findOrFail($comment_id);

        $repliesId = $comment->repliesTree()
            ->pluck('id')
            ->push($comment->id)
            ->toArray();

        Comment::whereIn('id', $repliesId)->delete();

        return response()->json([
            'status' => 'deleted',
            'message' => __('Deleted'),
        ]);
    }
}
