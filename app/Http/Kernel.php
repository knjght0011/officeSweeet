<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\SelectDB::class,
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\CheckApp::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'LoggedIn' => [
            \App\Http\Middleware\RedirectIfAuthenticated::class,
            \App\Http\Middleware\CheckForcePasswordChange::class,
            \App\Http\Middleware\SoloAdverts::class,
        ],

        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
        ],


    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        
        'management' => \App\Http\Middleware\management::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'RedirectIfAuthenticated' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'CheckPermission' => \App\Http\Middleware\CheckPermission::class,
        'CheckSubscription' => \App\Http\Middleware\CheckSubscription::class,
        #'CheckLLS' => \App\Http\Middleware\CheckLLS::class,
        'CheckSubdomain' => \App\Http\Middleware\CheckSubdomain::class,
        'CheckBroker' => \App\Http\Middleware\CheckBroker::class,
    ];
}
