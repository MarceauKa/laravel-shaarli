<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function all(Request $request)
    {
        return response()->json(
            Tag::select('name')
                ->latest()
                ->get()
                ->pluck('name')
                ->toArray()
        );
    }
}
