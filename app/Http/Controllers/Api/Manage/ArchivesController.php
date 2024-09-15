<?php

namespace App\Http\Controllers\Api\Manage;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkArchiveResource;
use App\Models\Link;
use Illuminate\Http\Request;

class ArchivesController extends Controller
{
    public function __construct()
    {
        $this->middleware('demo');
    }

    public function all(Request $request)
    {
        $archives = Link::whereNotNull('archive')->latest()->get();

        return response()->json(
            LinkArchiveResource::collection($archives)
        );
    }
}
