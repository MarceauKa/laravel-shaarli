<?php

namespace App\Http\Controllers;

use App\Models\Login;
use App\Notifications\SecureLoginCode;
use App\Models\SecureLogin;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\SessionGuard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Lab404\AuthChecker\Services\AuthChecker;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public $maxAttempts = 5;
    public $decayMinutes = 1;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function form()
    {
        return view('login');
    }

    public function check(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        if (true === app('shaark')->getSecureLogin()) {
            return $this->checkWithSecureLogin($request, $validated);
        }

        return $this->checkWithoutSecureLogin($request, $validated);
    }

    protected function checkWithoutSecureLogin(Request $request, array $validated)
    {
        if (Auth::guard()->attempt($validated, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            'email' => [__("Invalid credentials")],
        ]);
    }

    protected function checkWithSecureLogin(Request $request, array $validated)
    {
        /** @var SessionGuard $guard */
        $guard = Auth::guard();

        if ($guard->validate($validated)) {
            /** @var User $user */
            $user = $guard->getLastAttempted();

            /** @var AuthChecker $checker */
            $checker = app('authchecker');
            $device = $checker->findOrCreateUserDeviceByAgent($user);
            $checker->createUserLoginForDevice($user, $device, Login::TYPE_2FA);

            $secure = SecureLogin::createForUser($user);
            $user->notify(new SecureLoginCode($secure));

            return redirect()->route('login.secure', $secure);
        }

        event(new Failed('web', $guard->getLastAttempted(), $validated));

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            'email' => [__("Invalid credentials")],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    public function username(): string
    {
        return 'email';
    }
}
