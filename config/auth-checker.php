<?php

use App\Models\Login;

return [
    'throttle' => env('AUTH_CHECKER_THROTTLE', 0),
    'device_matching_attributes' => [
        # Ex: OS X, Windows, ...
        'platform',
        # Ex: 10_12_2, 8, ...
        'platform_version',
        # Ex: Chrome, Firefox, ...
        'browser',
        # Ex: 42.0.2311.135, 37.0, ...
        //'browser_version',
    ],
    'login_column' => 'email',
    'models' => [
        # Ex: App\Models\Device (default: Lab404\AuthChecker\Models\Device)
        'device' => null,
        # Ex: App\Models\Login (default: Lab404\AuthChecker\Models\Login)
        'login' => Login::class,
    ]
];
