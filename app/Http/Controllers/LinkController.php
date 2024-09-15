<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        return view('form-link')->with([
            'page_title' => __('Add link'),
            'query' => $request->query('url')
        ]);
    }

    public function edit(Request $request, int $id)
    {
        $link = Link::with('post.tags')->findOrFail($id);

        return view('form-link')->with([
            'page_title' => __('Update link'),
            'link' => $link,
        ]);
    }
}
