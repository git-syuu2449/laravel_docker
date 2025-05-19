<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

/**
 * アクセスログの出力を行うミドルウェア
 */
class AccessLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $log = sprintf(
            '[%s] %s %s from %s',
            now(),
            $request->method(),
            $request->fullUrl(),
            $request->ip(),
        );

        Log::channel('access')->info($log);

        return $response;
    }
}
