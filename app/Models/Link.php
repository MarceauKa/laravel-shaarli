<?php

namespace App\Models;

use App\Concerns\Models\Postable;
use App\Services\LinkPreview\LinkPreview;
use App\Services\Shaark\Shaark;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $int
 * @property string $title
 * @property string|null $content
 * @property string|null $preview
 * @property string|null $archive
 * @property string $url
 * @property bool $is_watched
 * @property string $http_status
 * @property Carbon|null $http_checked_at
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string $permalink
 * @property Post $post
 * @method self|Builder isWatched()
 * @method self|Builder isNotWatched()
 * @method self|Builder lastCheckedBefore(Carbon $date)
 * @method self|Builder healthStatusIs(string $status)
 */
class Link extends Model
{
    use Postable;

    const HEALTH_STATUS_LIVE = 'live';
    const HEALTH_STATUS_DEAD = 'dead';
    const HEALTH_STATUS_ERROR = 'error';
    const HEALTH_STATUS_REDIRECT = 'redirect';
    const HEALTH_STATUS = [
        self::HEALTH_STATUS_LIVE,
        self::HEALTH_STATUS_DEAD,
        self::HEALTH_STATUS_ERROR,
        self::HEALTH_STATUS_REDIRECT,
    ];

    protected $fillable = [
        'title',
        'content',
        'preview',
        'archive',
        'url',
        'is_watched',
    ];
    protected $appends = [
        'permalink',
    ];
    protected $casts = [
        'is_watched' => 'bool',
        'http_checked_at' => 'datetime',
    ];
    protected $touches = ['post'];

    public function getHashIdAttribute(): string
    {
        return app('hashid')->encode($this->id);
    }

    public function getPermalinkAttribute(): string
    {
        return route('link.view', $this->hash_id);
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->attributes['url'])) {
            return $this->permalink;
        }

        return $this->attributes['url'];
    }

    public function setIsWatchedAttribute($value): void
    {
        if (app(Shaark::class)->getLinkHealthChecksEnabled()) {
            $this->attributes['is_watched'] = false;
        }

        $this->attributes['is_watched'] = in_array($value, ['on', true, '1', 1]);

        if ($this->attributes['is_watched'] === false) {
            $this->attributes['http_status'] = null;
            $this->attributes['http_checked_at'] = null;
        }
    }

    public function scopeHashIdIs(Builder $query, string $hash): Builder
    {
        return $query->where('id', app('hashid')->decode($hash));
    }

    public function scopeIsWatched(Builder $query): Builder
    {
        return $query->where('is_watched', 1);
    }

    public function scopeIsNotWatched(Builder $query): Builder
    {
        return $query->where('is_watched', 0);
    }

    public function scopeHealthStatusIs(Builder $query, string $status): Builder
    {
        switch ($status) {
            case self::HEALTH_STATUS_LIVE:
                return $query->whereBetween('http_status', [200, 299]);
            case self::HEALTH_STATUS_DEAD:
                return $query->whereBetween('http_status', [400, 499]);
            case self::HEALTH_STATUS_ERROR:
                return $query->whereBetween('http_status', [500, 599]);
            case self::HEALTH_STATUS_REDIRECT:
                return $query->whereBetween('http_status', [300, 399]);
            default:
                return $query->whereNull('http_status');
        }
    }

    public function scopeLastCheckedBefore(Builder $query, Carbon $date): Builder
    {
        return $query->where(function (Builder $query) use ($date) {
            return $query
                ->where('http_checked_at', '<', $date->toDateTimeString())
                ->orWhereNull('http_checked_at');
        });
    }

    public function updatePreview(): self
    {
        $preview = LinkPreview::preview($this->url);

        if ($preview) {
            $this->attributes['preview'] = $preview;
            $this->save();
        }

        return $this;
    }

    public function hasArchive(): bool
    {
        return !empty($this->archive) && Storage::disk('archives')->exists($this->archive);
    }

    public function canDownloadArchive(): bool
    {
        if (false === $this->hasArchive()) {
            return false;
        }

        if ($this->post->is_private && auth()->check() === false) {
            return false;
        }

        if (app('shaark')->getPrivateDownload() === true && auth()->check() === false) {
            return false;
        }

        return true;
    }
}
