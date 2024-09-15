<?php

namespace App\Models;

use App\Concerns\Models\Postable;
use App\Services\Shaark\Shaark;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Album extends Model implements HasMedia
{
    use Postable,
        InteractsWithMedia;

    static public $mimes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/png',
        'image/webp',
        'image/svg+xml',
        'image/bmp',
    ];
    protected $fillable = [
        'title',
        'content',
    ];
    protected $appends = [
        'permalink',
    ];
    protected $touches = ['post'];

    public function getHashIdAttribute(): string
    {
        return app('hashid')->encode($this->id);
    }

    public function getPermalinkAttribute(): string
    {
        return route('album.view', $this->hash_id);
    }

    public function scopeHashIdIs(Builder $query, string $hash): Builder
    {
        return $query->where('id', app('hashid')->decode($hash));
    }

    public function canDownloadArchive(): bool
    {
        if ($this->post->is_private
            && auth()->check() === false) {
            return false;
        }

        if (app('shaark')->getPrivateDownload() === true
            && auth()->check() === false) {
            return false;
        }

        return true;
    }

    public function registerMediaCollections() :void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(self::$mimes)
            ->useDisk('albums');
    }

    public function registerMediaConversions(Media $media = null) : void
    {
        /** @var Shaark $shaark */
        $shaark = app('shaark');
        $conversion = $this->addMediaConversion('thumb');

        if ($shaark->getImagesThumbFormat() === 'square') {
            $conversion->fit(Manipulations::FIT_CROP, 300, 300);
        }

        if ($shaark->getImagesThumbFormat() === 'original') {
            $conversion->fit(Manipulations::FIT_CONTAIN, 300, 300);
        }

        if ($shaark->getImagesThumbQueue() === false) {
            $conversion->nonQueued();
        }
    }
}
