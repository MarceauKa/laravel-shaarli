<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserAccount;
use App\Http\Requests\UpdateUserPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lab404\AuthChecker\Models\Login;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('demo')->except('form');
    }

    public function form(Request $request)
    {
        return view('account')->with([
            'page_title' => __('Account'),
            'user' => $request->user(),
            'tab' => 'general',
        ]);
    }

    public function formPassword(Request $request)
    {
        return view('account')->with([
            'page_title' => __('Account'),
            'user' => $request->user(),
            'tab' => 'password'
        ]);
    }

    public function viewLogins(Request $request)
    {
        $user = $request->user();
        $logins = Login::where('user_id', $user->id)->latest()->paginate(25);

        return view('account')->with([
            'page_title' => __('Account'),
            'user' => $user,
            'logins' => $logins,
            'tab' => 'logins'
        ]);
    }

    public function store(UpdateUserAccount $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->fill($validated);
        $user->save();

        $this->flash(__('Your account has been updated!'));
        return redirect()->back();
    }

    public function storePassword(UpdateUserPassword $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->password = Hash::make($validated['new_password']);
        $user->api_token = User::generateApiToken();
        $user->save();

        $this->flash(__('Your password has been updated!'));
        return redirect()->back();
    }

    public function logoutDevices(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::guard('web')->logoutOtherDevices($validated['password']);

        $user = $request->user();
        $user->api_token = User::generateApiToken();
        $user->save();

        $this->flash(__("Other sessions have been logged out"), 'success');
        return redirect()->back();
    }
}
