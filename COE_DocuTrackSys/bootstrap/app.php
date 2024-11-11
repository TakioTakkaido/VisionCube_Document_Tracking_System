<?php

// VISION CUBE SOFTWARE CO. 
// Application Configuration
// Contributor/s: 
// Calulut, Joshua Miguel C

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\UserRoleMiddleware;
use App\Http\Middleware\VerifyAccount;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(VerifyAccount::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
