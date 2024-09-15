<?php

namespace App\Http\Controllers\Api\Manage;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;

class LinksHealthController extends Controller
{
    public function __construct()
    {
        $this->middleware('demo');
    }

    public function get(Request $request, string $type)
    {
        if (false === in_array($type, Link::HEALTH_STATUS)) {
            abort(404);
        }

        $links = Link::isWatched()
            ->healthStatusIs($type)
            ->withPrivate($request->user('api'))
            ->get();

        return LinkResource::collection($links);
    }
}
