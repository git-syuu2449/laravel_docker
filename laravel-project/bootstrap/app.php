<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;

// Middleware
use App\Http\Middleware\AccessLogMiddleware;
use App\Http\Middleware\RoleMiddleware;

// Exception
use App\Exceptions\CustomExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
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
    })
    // 
    ->withProviders([  
    ])
    ->create();