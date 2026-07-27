<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'role.admin' => \App\Http\Middleware\RoleAdmin::class,
        ]);

        // Railway/Render menempatkan aplikasi di belakang reverse proxy HTTPS.
        // Tanpa ini, Laravel mengira request selalu HTTP biasa,
        // sehingga asset (css/js) ke-generate pakai http:// dan diblokir browser.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
