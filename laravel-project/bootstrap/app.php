<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Middleware
use App\Http\Middleware\AccessLogMiddleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // 一律で動作するミドルウェア
    ->withMiddleware(function (Middleware $middleware) {
        // アクセスログ
        $middleware->append(AccessLogMiddleware::class);
        // 権限ミドルウェアのエイリアスを割り当て（実行はweb.phpで定義）
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
