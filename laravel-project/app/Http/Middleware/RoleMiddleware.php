<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

use App\Enums\Role;

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
        // 未ログインで権限が必要な機能にアクセス
        // ログインチェック機能後に呼ばれる為通常起こり得ない
        if (!$request->user())
        {
            abort(403, 'Access denied');
        }

        // 管理者の場合は一般ユーザーの機能が使用可能
        if ($request->user()->role === Role::Admin->value) {
            return $next($request);
        }

        // 権限一致チェック
        if ($request->user()->role->value !== $role) {

            // API用
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
