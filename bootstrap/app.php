<?php

use App\Exceptions\ApplicationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
            ->group('web', [
                \App\Http\Middleware\RedirectRenamedPages::class,
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \App\Http\Middleware\RegisterVisitor::class,
            ])
            ->group('api', [
                \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
                \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
                \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \App\Http\Middleware\RegisterVisitor::class,
                \Spatie\Honeypot\ProtectAgainstSpam::class,
                \App\Http\Middleware\VisitorBanned::class,
                \App\Http\Middleware\LogRequest::class,
            ])
            ->group('admin', [
                \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
                \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions
            ->dontReport(ApplicationException::class)
            ->render(function (Throwable $e, Request $request){
                if ($request->is('api/*')) {
                    return response()->error($e);
                }
            });

    })->create();
