<?php

use App\Enums\Role;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

# 独自追加
use App\Http\Controllers\Dev\ErrorDebugController;
use App\Http\Controllers\Dev\RoutesController;

// エラーハンドリングの検証(debug時)
if (config('app.debug')) {
    Route::get('/debug-error_500', function () {
        abort(500, 'テスト用の500エラー');
    });
    Route::controller(ErrorDebugController::class)
    ->prefix('debug-error')
    ->as('debug-error')
    ->group(function () {
        Route::get('500', 'exeption_500')->name('500');
        Route::get('abort_500', 'exeption_abort_500')->name('abort_500');
    });
    // 自動テスト用
    Route::get('/debug-domain', function () {
    // return response('Domain: ' . var_export(config('session.domain'), true));
        return response('Session Domain: ' . var_export(config('session.domain'), true) . ' | App URL: ' . config('app.url'));
    });
    Route::get('/debug-sanctum-domains', function () {
        return response()->json(config('sanctum.stateful'));
    });
    Route::get('/debug-app-url', function () {
    return response()->json([
        'app_url_env' => env('APP_URL'),
        'parsed_app_url_host' => parse_url(env('APP_URL'), PHP_URL_HOST),
        'base_url_config' => config('app.url'),
    ]);
});

}

// 検証用のURL一覧
if (config('app.debug')) {
    Route::controller(RoutesController::class)
    ->prefix('dev')
    ->as('dev.')
    ->group(function () {
        Route::get('routes', 'index')->name('routes');
    });
}