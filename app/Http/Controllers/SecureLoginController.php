<?php

namespace App\Http\Controllers;

use App\Models\SecureLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SecureLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:5,1')->only('check');

        $this->middleware('demo')->except('form');
    }

    public function form(Request $request, SecureLogin $secure)
    {
        return view('login-secure')->with([
            'token' => $secure->token,
            'expires' => $secure->expires_at,
        ]);
    }

    public function check(Request $request, SecureLogin $secure)
    {
        $validated = $request->validate([
            'code' => 'required',
        ]);

        if ($secure->code === $validated['code']) {
            $request->session()->regenerate();

            Auth::login($secure->user, $request->filled('remember'));
            SecureLogin::where('user_id', $secure->user_id)->delete();

            return redirect('/');
        }

        throw ValidationException::withMessages([
            'code' => [__("Invalid secure code")],
        ]);
    }
}
