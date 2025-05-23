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