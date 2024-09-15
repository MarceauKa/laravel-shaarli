<?php

namespace App\Http\Requests;

use App\Models\Comment;
use App\Models\Post;
use App\Services\Shaark\Shaark;
use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
{
    /** @var Post $post */
    public $post;

    public function authorize(Shaark $shaark)
    {
        $this->post = Post::withPrivate($this->user('api'))
                        ->findOrFail($this->route('id'));

        return $shaark->authorizedToAddComments($this);
    }

    public function rules()
    {
        $rules = [
            'content' => [
                'required',
                'min:10',
                'max:3000',
            ],
            'reply' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! empty($value)) {
                        $comment = Comment::postIs($this->post->id)
                            ->findOrFail($value);
                    }
                },
            ]
        ];

        if (empty($this->user('api'))) {
            $rules['name'] = [
                'required',
                'min:2',
            ];

            $rules['email'] = [
                'required',
                'email'
            ];

            $rules['captcha'] = [
                'required',
                'captcha_api:' . $this->key,
            ];
        }

        return $rules;
    }
}
