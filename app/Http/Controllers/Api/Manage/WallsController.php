<?php

namespace App\Http\Controllers\Api\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWallRequest;
use App\Models\Wall;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WallsController extends Controller
{
    public function __construct()
    {
        $this->middleware('demo')->except('all');
    }

    public function all(Request $request)
    {
        $walls = Wall::withPrivate($request)
            ->get();

        return response()->json($walls);
    }

    public function store(StoreWallRequest $request)
    {
        $data = collect($request->validated());

        $wall = new Wall($data->only('title', 'slug', 'is_private', 'is_default', 'appearance', 'restrict_tags', 'restrict_cards')->toArray());
        $wall->appearance = $request;
        $wall->save();

        return response()->json([
            'wall' => $wall,
            'status' => 'created',
        ]);
    }

    public function update(StoreWallRequest $request, int $id)
    {
        /** @var Model $wall */
        $wall = Wall::withPrivate($request->user('api'))->findOrFail($id);
        $data = collect($request->validated());

        $wall->fill($data->only('title', 'slug', 'is_private', 'is_default', 'appearance', 'restrict_tags', 'restrict_cards')->toArray());
        $wall->appearance = $request;
        $wall->save();

        return response()->json([
            'wall' => $wall,
            'status' => 'updated',
        ]);
    }

    public function delete(Request $request, int $id)
    {
        if (Wall::withPrivate($request->user('api'))->count() === 1) {
            return response()->json([
                'status' => 'error',
                'message' => "Can't delete the last wall",
            ], 422);
        }

        $wall = Wall::withPrivate($request->user('api'))->findOrFail($id);
        $wall->delete();

        return response()->json([
            'wall' => $wall,
            'status' => 'deleted',
        ]);
    }
}
