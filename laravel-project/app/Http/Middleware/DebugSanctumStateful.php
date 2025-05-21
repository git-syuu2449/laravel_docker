<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugSanctumStateful
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('[Sanctum Debug] cookies: ', $request->cookies->all());
        Log::info('[Sanctum Debug] session: ', $request->session()->all());
        Log::info('[Sanctum Debug] user: ', ['user' => $request->user()]);

        return $next($request);
    }
}
