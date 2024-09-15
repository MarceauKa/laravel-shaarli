<?php

namespace App\Http\Requests;

use App\Models\Album;
use Illuminate\Foundation\Http\FormRequest;

class StoreAlbumUploadRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'filepond' => [
                'required',
                'image',
                'mimetypes:' . implode(',', Album::$mimes),
            ]
        ];
    }
}
