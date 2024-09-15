<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        return view('form-story')->with([
            'page_title' => __('Add story'),
        ]);
    }

    public function edit(Request $request, int $id)
    {
        $story = Story::with('post.tags')->findOrFail($id);

        return view('form-story')->with([
            'page_title' => __('Update story'),
            'story' => $story,
        ]);
    }
}
