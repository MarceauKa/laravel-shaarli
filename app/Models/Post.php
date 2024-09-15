<?php

namespace App\Models;

use App\Concerns\Models\HasTags;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

/**
 * @method Builder|Post pinnedFirst()
 * @method Builder|Post withPrivate(User|Request $private)
 * @method Builder|Post withoutChests()
 */
class Post extends Model
{
    use HasTags,
        Searchable;

    protected $fillable = [
        'postable_type',
        'postable_id',
        'is_private',
        'is_pinned',
        'user_id',
        'created_at',
    ];
    protected $casts = [
        'is_pinned' => 'bool',
        'is_private' => 'bool',
    ];

    public function postable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Share::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getCreatedAtFormatedAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function setIsPinnedAttribute($value): void
    {
        if ($value instanceof Collection) {
            $value = (bool)$value->get('is_pinned', false);
        }

        $this->attributes['is_pinned'] = $value;
    }

    public function setIsPrivateAttribute($value): void
    {
        if ($value instanceof Collection) {
            $value = (bool)$value->get('is_private', false);
        }

        $this->attributes['is_private'] = $value;
    }

    public function scopeWithPrivate(Builder $query, $user = null): Builder
    {
        if ($user instanceof Request) {
            $user = $user->user();
        }

        if (empty($user)) {
            return $query->where('is_private', 0);
        }

        if ($user->is_admin === false) {
            return $query
                ->where('is_private', 0)
                ->orWhere(function ($query) use ($user) {
                    return $query
                        ->where('is_private', 1)
                        ->where('user_id', $user->id);
                });
        }

        return $query;
    }

    public function scopeWithWallRestrictions(Builder $query, ?array $tags, ?array $cards): Builder
    {
        $tags = $tags ?? [];
        $cards = $cards ?? [];

        // Restrict on tags list
        if (count($tags) > 0) {
            $this->scopeWithAnyTags($query, $tags);
        }

        // Restrict on card types
        if (count($cards) > 0) {
            $classes = [
                'link' => 'App\\Models\\Link',
                'chest' => 'App\\Models\\Chest',
                'story' => 'App\\Models\\Story',
                'album' => 'App\\Models\\Album',
            ];

            $types = collect($cards)
                ->transform(function ($item) use ($classes) {
                    return array_key_exists($item, $classes) ? $classes[$item] : null;
                })
                ->reject(function ($item) {
                    return is_null($item);
                })
                ->toArray();

            $query->whereIn('postable_type', $types);
        }

        return $query;
    }

    public function scopePinnedFirst(Builder $query): Builder
    {
        return $query->orderByDesc('is_pinned');
    }

    public function scopeWithoutChests(Builder $query): Builder
    {
        return $query->where('postable_type', '!=', Chest::class);
    }

    public function scopeLinksWithArchive(Builder $query): Builder
    {
        return $query->whereHasMorph('postable', Link::class, function (Builder $query) {
            return $query->whereNotNull('archive');
        });
    }

    public function toSearchableArray()
    {
        return array_merge([
            'id' => $this->id,
            'date' => $this->created_at->toDateTimeString(),
        ], $this->postable->toSearchableArray());
    }
}
