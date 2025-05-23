<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * 開発用のURL一覧用
 */
class RoutesController extends Controller
{
    public function index()
    {
        $routes = collect(Route::getRoutes())
            ->filter(fn($r) => in_array('GET', $r->methods()) || in_array('POST', $r->methods()))
            ->map(fn($r) => [
                'uri' => $r->uri(),
                'methods' => $r->methods(),
                'name' => $r->getName(),
            ])
            ->values();

        // アクションごとのPOSTフィールド構成
        $postForms = [
            'questions.store' => [
                'question_text' => ['type' => 'input', 'value' => '仮の質問'],
                // 'category' => ['type' => 'select', 'options' => ['math', 'science']],
            ],
            'users' => [
                'name' => ['type' => 'input', 'value' => '山田太郎'],
                'email' => ['type' => 'input', 'value' => 'test@example.com'],
            ],
        ];

        return view('dev.routes', compact('routes', 'postForms'));
    }
}
