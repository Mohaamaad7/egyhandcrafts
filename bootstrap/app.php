<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

require_once __DIR__.'/../app/helpers.php';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(fn () => '/' . admin_path());
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('forgot-password') && ! ($e instanceof \Illuminate\Validation\ValidationException) && ! ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface)) {
                return back()->with('status', 'تم استلام طلبك. نظراً لعدم ربط خادم البريد في بيئة العمل الحالية، يُرجى مراجعة مدير النظام لإعادة تعيين كلمة المرور يدوياً.');
            }
        });
    })->create();
