<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlbumRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'min:2',
                'max:255',
            ],
            'content' => [
                'nullable',
                'max:10000',
            ],
            'is_private' => [
                'nullable',
            ],
            'is_pinned' => [
                'nullable',
            ],
            'uploaded' => [
                'nullable',
                'array',
            ],
            'uploaded.*' => [
                'temp_image',
            ],
            'images' => [
                'nullable',
                'array',
            ],
            'images.*.name' => [
                'required'
            ],
            'images.*.order' => [
                'numeric',
                'min:0',
                'max:9999'
            ],
            'tags' => [
                'nullable',
                'array',
                'max:10',
            ],
            'tags.*' => [
                'min:1',
                'max:30',
            ],
        ];
    }
}
