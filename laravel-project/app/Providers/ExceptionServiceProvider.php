<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use App\Exceptions\CustomExceptionHandler;

class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // dd(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10)); // deubg
        // カスタム例外ハンドラをバインド
        $this->app->singleton(ExceptionHandlerContract::class, function ($app) {
            return new CustomExceptionHandler($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
