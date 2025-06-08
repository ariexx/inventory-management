<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\EncryptCookies::class,
        \App\Http\Middleware\AddQueuedCookiesToResponse::class,
        \App\Http\Middleware\StartSession::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \App\Http\Middleware\SetCacheHeaders::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\RedirectIfAuthenticated::class,
        \App\Http\Middleware\EncryptCookies::class,
        \App\Http\Middleware\AddQueuedCookiesToResponse::class,
        \App\Http\Middleware\StartSession::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \App\Http\Middleware\SetCacheHeaders::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'record.login' => \App\Http\Middleware\RecordLoginActivity::class,
    ];
}
