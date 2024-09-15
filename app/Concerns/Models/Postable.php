<?php

namespace App\Concerns\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;

/**
 * @property string $title
 * @property string $content
 * @method Builder withPrivate(bool|User|Request $private)
 */
trait Postable
{
    public function post(): MorphOne
    {
        return $this->morphOne(Post::class, 'postable');
    }

    public function scopeWithPrivate(Builder $query, $user = null): Builder
    {
        return $query->whereHas('post', function ($query) use ($user) {
            return $query->withPrivate($user);
        });
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
