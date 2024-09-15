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

class LinkHealthCheck
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Link $link */
    public $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }
}
