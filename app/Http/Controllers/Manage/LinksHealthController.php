<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Services\Shaark\Shaark;
use Illuminate\Support\Facades\Cache;

class LinksHealthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('demo')->except('view');
    }

    public function view(Shaark $shaark)
    {
        $stats = Cache::remember('health-checks', now()->addHour(), function () {
            return [
                'total' => Link::isWatched()->count(),
                'live' => Link::isWatched()->healthStatusIs(Link::HEALTH_STATUS_LIVE)->count(),
                'dead' => Link::isWatched()->healthStatusIs(Link::HEALTH_STATUS_DEAD)->count(),
                'error' => Link::isWatched()->healthStatusIs(Link::HEALTH_STATUS_ERROR)->count(),
                'redirect' => Link::isWatched()->healthStatusIs(Link::HEALTH_STATUS_REDIRECT)->count(),
                'disabled' => Link::isNotWatched()->count(),
            ];
        });

        return view('manage.links')->with([
            'page_title' => __('Links'),
            'enabled' => $shaark->getLinkHealthChecksEnabled(),
            'stats' => $stats,
        ]);
    }
}
