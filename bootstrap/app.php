<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

           // Prepend StartSession to ensure sessions are started
        $middleware->prepend(StartSession::class);

        // Append your localization middleware
        $middleware->append(SetLocale::class);



    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
