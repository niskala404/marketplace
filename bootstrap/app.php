<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Capture affiliate codes (?aff=XXXX) into session + audit logs for admin actions
        $middleware->web(append: [
            \App\Http\Middleware\CaptureAffiliateCode::class,
            \App\Http\Middleware\AdminAuditLogger::class,
        ]);

        // Allow payment gateway webhooks
        $middleware->validateCsrfTokens(except: [
            'payments/midtrans/notify',
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            // Seller must create a shop before accessing most seller pages
            'seller.shop' => \App\Http\Middleware\EnsureSellerHasShop::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        //
    })->create();

