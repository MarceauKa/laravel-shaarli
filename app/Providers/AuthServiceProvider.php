<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {

        Gate::define('restricted', function (?User $user) {
            if (app('shaark')->getIsPrivate() === false) {
                return true;
            }

            return !empty($user);
        });

        Gate::define('comments.see', function (?User $user) {
            return app('shaark')->authorizedToSeeComments(request());
        });

        Gate::define('comments.add', function (?User $user) {
            return app('shaark')->authorizedToAddComments(request());
        });
    }
}
