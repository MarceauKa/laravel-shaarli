<?php

namespace App\Http\Requests;

use App\Models\Wall;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWallRequest extends FormRequest
{
    public function authorize()
    {
        if ($this->route('id')) {
            $wall = Wall::withPrivate($this->user('api'))->findOrFail($this->route('id'));
        }

        return auth()->check();
    }

    public function rules()
    {
        $unique = Rule::unique('walls');

        if ($this->route('id')) {
            $unique->ignore($this->route('id'), 'id');
        }

        return [
            'title' => [
                'required',
                'min:2',
                'max:255',
            ],
            'slug' => [
                'required',
                $unique,
                'max:255',
            ],
            'is_private' => [
                'nullable',
            ],
            'is_default' => [
                'nullable',
            ],
            'restrict_tags' => [
                'nullable',
                'array',
            ],
            'restrict_cards' => [
                'nullable',
                'array',
            ],
            'restrict_cards.*' => [
                'in:link,story,chest,album',
            ],
            'appearance.columns' => [
                'numeric',
                'min:1',
                'max:4'
            ],
            'appearance.compact' => [
                'nullable',
            ],
            'appearance.show_tags' => [
                'nullable',
            ],
        ];
    }
}
