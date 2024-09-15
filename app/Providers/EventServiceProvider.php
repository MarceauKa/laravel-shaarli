<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\LinkArchiveRequested::class => [
            \App\Listeners\MakeLinkArchive::class
        ],
        \App\Events\LinkHealthCheck::class => [
            \App\Listeners\CheckLinkHealth::class
        ],
        \Illuminate\Database\Events\MigrationEnded::class => [
            \App\Listeners\UpdateDatabase::class
        ],
    ];

    public function boot()
    {
        parent::boot();

        \App\Models\Comment::observe(\App\Observers\CommentObserver::class);
        \App\Models\Tag::observe(\App\Observers\TagObserver::class);
        \App\Models\Wall::observe(\App\Observers\WallObserver::class);
    }
}
