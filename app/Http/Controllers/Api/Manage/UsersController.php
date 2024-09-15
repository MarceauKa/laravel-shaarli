<?php

namespace App\Http\Controllers\Api\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\StoreUserRequest;
use App\Http\Requests\Manage\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('demo')->except('all');
    }

    public function all()
    {
        return UserResource::collection(User::all());
    }

    public function store(StoreUserRequest $request)
    {
        $validated = collect($request->validated());

        $user = new User($validated->only([
            'name',
            'email'
        ])->toArray());

        $user->password = Hash::make($validated->get('password'));
        $user->api_token = User::generateApiToken();
        $user->is_admin = $validated->get('is_admin', false);
        $user->save();

        return response()->json([
            'status' => 'created',
            'id' => $user->id,
        ]);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $validated = collect($request->validated());
        $user = User::findOrFail($id);

        $user->fill($validated->only('name', 'email')->toArray());

        $user->is_admin = $validated->get('is_admin', 0) == 1;

        if ($validated->get('password')) {
            $user->password = Hash::make($validated->get('password'));
            $user->api_token = User::generateApiToken();
        }

        $user->save();

        return response()->json([
            'status' => 'updated',
            'id' => $user->id,
        ]);
    }

    public function delete(Request $request, int $id)
    {
        if ($request->user()->id == $id) {
            return response()->json([
               'status' => 'error',
               'message' => __("Can't delete the user you're logged in"),
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->delete();

        $admin = User::isAdmin()->first();

        if ($admin) {
            Post::where('user_id', $id)->update(['user_id' => $admin->id]);
        } else {
            Post::where('user_id', $id)->update(['user_id' => null]);
        }

        return response()->json([
            'status' => 'deleted',
            'id' => $id,
        ]);
    }
}
