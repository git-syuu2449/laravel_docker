<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

/**
 * ユーザーのロールに関連したミドルウェア
 */
class RoleMiddleware
{
    /**
     * ロールのチェックを行う
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @param string $department
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role, string $department = null): Response
    {
        Log::debug('request-user: '.$request->user());
        // 管理者の場合は一般ユーザーの機能が使用可能
        if (!$request->user()->role === $request->user()->role::Admin) {
            // 権限一致チェック
            if (!$request->user() || $request->user()->role !== $role) {
                abort(403, 'Access denied');
            }
        }

        return $next($request);
    }
}
