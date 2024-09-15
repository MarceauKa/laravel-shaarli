<?php

namespace Tests\Browser;

use App\Models\SecureLogin;
use App\Services\Shaark\Shaark;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_tests_login()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('randomPassword'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->assertGuest()
                ->visitRoute('account')
                ->waitForRoute('login')
                ->assertRouteIs('login')
                ->assertSee("Login")
                ->type('email', $user->email)
                ->type('password', 'wrongPassword')
                ->press("Login")
                ->assertRouteIs('login')
                ->assertSee("Invalid credentials")
                ->type('email', $user->email)
                ->type('password', 'randomPassword')
                ->press("Login")
                ->assertSee($user->name);
        });
    }

    /** @test */
    public function it_tests_login_with_2fa()
    {
        $this->shaark()->setSecureLogin(true);

        $user = factory(User::class)->create([
            'password' => Hash::make('randomPassword'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->visitRoute('login')
                ->type('email', $user->email)
                ->type('password', 'randomPassword')
                ->press('Login');

            $secure = SecureLogin::first();

            $browser
                ->assertRouteIs('login.secure', $secure->token)
                ->assertSee("Secure login")
                ->type('code', Str::random(8))
                ->press("Login")
                ->waitForText("Invalid secure code")
                ->type('code', $secure->code)
                ->press("Login")
                ->assertSee($user->name);
        });

        $this->shaark()->setSecureLogin(false);
    }
}
