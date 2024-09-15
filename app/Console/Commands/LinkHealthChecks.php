<?php

namespace App\Console\Commands;

use App\Events\LinkHealthCheck;
use App\Models\Link;
use App\Services\Shaark\Shaark;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class LinkHealthChecks extends Command
{
    protected $signature = 'shaark:watch-links';
    protected $description = 'Run health checks on links';

    public function handle()
    {
        Link::query()
            ->isWatched()
            ->lastCheckedBefore(now()->subDays(app(Shaark::class)->getLinkHealthChecksAge()))
            ->chunk(10, function ($links) {
                /** @var Link[]|Collection $links */
                /** @var Link $link */
                foreach ($links as $link) {
                    LinkHealthCheck::dispatch($link);
                }
            });
    }
}
