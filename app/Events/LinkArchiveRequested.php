<?php

namespace App\Events;

use App\Models\Link;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LinkArchiveRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Link $link */
    public $link;
    /** @var string $provider */
    public $provider;

    public function __construct(Link $link, string $provider)
    {
        $this->link = $link;
        $this->provider = $provider;
    }
}
