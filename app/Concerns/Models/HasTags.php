<?php

namespace App\Concerns\Models;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @method MorphToMany morphToMany(string $model, string $relation)
 */
trait HasTags
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }

    /**
     * @param string|Tag $tag
     * @return self
     */
    public function attachTag($tag): self
    {
        $this->attachTags([$tag]);

        return $this;
    }

    /**
     * @param string|array|Tag[] $tags
     * @return self
     */
    public function attachTags($tags): self
    {
        $this->tags()->syncWithoutDetaching(
            $this->convertToTags($tags)
                ->pluck('id')
                ->toArray()
        );

        return $this;
    }

    /**
     * @param string|array|Tag[] $tags
     * @return HasTags
     */
    public function syncTags($tags): self
    {
        $this->tags()->sync(
            $this->convertToTags($tags)
                ->pluck('id')
                ->toArray()
        );

        return $this;
    }

    public function scopeWithAnyTags(Builder $query, $tags): Builder
    {
        $tags = $this->convertToTags($tags);

        return $query->whereHas('tags', function ($query) use ($tags) {
            $query->whereIn('id', $tags->pluck('id')->toArray());
        });
    }

    public function scopeWithAllTags(Builder $query, $tags): Builder
    {
        $this->convertToTags($tags)
            ->each(function ($tag) use ($query) {
                $query->whereHas('tags', function ($query) use ($tag) {
                    $query->where('id', $tag->id);
                });
            });

        return $query;
    }

    /**
     * @param array|string|Tag[] $values
     * @return Collection
     */
    protected function convertToTags($values): Collection
    {
        return collect(is_array($values) ? $values : [$values])
            ->map(function ($value) {
                if ($value instanceof Tag) {
                    return $value;
                }

                return Tag::firstOrCreate([
                    'name' => Str::slug($value)
                ]);
            });
    }
}
