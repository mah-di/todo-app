<?php

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.api' => \App\Http\Middleware\APIAuthenticate::class,
            'guest.api' => \App\Http\Middleware\APIEnsureGuest::class,
        ]);
        
        $middleware->prependToGroup('api', [
            \App\Http\Middleware\APIHandleUnauthorizedAccess::class
        ]);

        $middleware->appendToGroup('api', [
            EncryptCookies::class,
            StartSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn (Request $request) => $request->is('api/*') || $request->wantsJson());
    })->create();
