<?php

namespace App\Http\Resources;

use App\Models\Album;
use App\Models\Chest;
use App\Models\Link;
use App\Models\Story;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->resource->postable instanceof Link) {
            $content = [
                'title' => $this->postable->title,
                'content' => $this->postable->content,
                'url' => $this->postable->permalink,
                'type' => __('Link'),
            ];
        } elseif ($this->resource->postable instanceof Story) {
            $content = [
                'title' => $this->postable->title,
                'slug' => $this->postable->slug,
                'content' => $this->postable->content,
                'url' => $this->postable->url,
                'type' => __('Story'),
            ];
        } elseif ($this->resource->postable instanceof Chest) {
            $content = [
                'title' => $this->postable->title,
                'url' => $this->postable->permalink,
                'type' => __('Chest'),
            ];
        } elseif ($this->resource->postable instanceof Album) {
            $content = [
                'title' => $this->postable->title,
                'url' => $this->postable->permalink,
                'type' => __('Album'),
            ];
        }

        return array_merge($content, [
            'id' => $this->id,
            'postable_id' => $this->postable->id,
            'tags' => $this->tags->pluck('name')->toArray(),
            'is_private' => $this->is_private,
            'is_pinned' => $this->is_pinned,
            'created_at' => $this->created_at,
        ]);
    }
}
