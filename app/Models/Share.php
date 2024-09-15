<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @method Builder tokenIs(string $token)
 */
class Share extends Model
{
    protected $fillable = [
        'post_id',
        'token',
        'expires_at',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getUrlAttribute(): string
    {
        return route('share', [$this->id, $this->token]);
    }

    public function scopePostIs(Builder $query, int $id): Builder
    {
        return $query->where('post_id', $id);
    }

    public function scopeTokenIs(Builder $query, string $token): Builder
    {
        return $query->where('token', $token);
    }

    public function setExpiration(string $expiration): self
    {
        $expires = Carbon::now();

        switch ($expiration) {
            case 'month':
                $expires->addMonth();
                break;
            case 'weeks':
                $expires->addWeeks(2);
                break;
            case 'week':
                $expires->addWeek();
                break;
            case 'days':
                $expires->addDays(3);
                break;
            case 'day':
                $expires->addDay();
                break;
            case 'hours':
                $expires->addHours(12);
                break;
            default:
            case 'hour':
                $expires->addHour();
                break;
        }

        $this->expires_at = $expires;
        return $this;
    }

    public function generateToken(): self
    {
        $this->token = Str::random(64);
        return $this;
    }

    public static function clearExpired(): void
    {
        Share::where('expires_at', '<', Carbon::now()->toDateTimeString())
            ->delete();
    }
}
