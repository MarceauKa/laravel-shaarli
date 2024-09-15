<?php

namespace App\Http\Controllers;

use App\Models\Chest;
use Illuminate\Http\Request;

class ChestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        return view('form-chest')->with([
            'page_title' => __('Add chest'),
        ]);
    }

    public function edit(Request $request, int $id)
    {
        $chest = Chest::with('post.tags')->findOrFail($id);

        return view('form-chest')->with([
            'page_title' => __('Update chest'),
            'chest' => $chest,
        ]);
    }
}
