<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Enums\Role;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\ChoiceController;

use App\Http\Controllers\Api\Dashboard\QuestionController as DashboardQuestionController;
use App\Http\Controllers\Api\Dashboard\ChoiceController as DashboardChoiceController;
use App\Http\Controllers\Api\RankingController as ApiRankingController;
use App\Http\Controllers\RankingController;

// 自動でprefixにapi/が付与

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get(uri: '/questions', action: [QuestionController::class, 'index'])->name('api.questions.index')
// ->middleware(['web', 'auth:sanctum' ,'role:'.Role::User->value]);

// question
Route::controller(QuestionController::class)
->prefix('questions')
->as('api.questions.')
->middleware(['web', 'auth:sanctum', 'role:'.Role::User->value])
->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::delete('/{id}', 'destroy')->name('destroy');
});

// questions/choice
Route::controller(ChoiceController::class)
// ->get('/', 'index')->name('index') // questions以下ではない為別途切り出し
->prefix('questions/{question_id}/choices')
->as('api.questions.choices.')
->middleware(['web', 'auth:sanctum', 'role:'.Role::User->value])
->group(function () {
    Route::get('/', 'index')->name('index');
    // Route::get('/', 'indexAll')->name('indexAll')->prefix('choices');
    Route::get('/{choice_id}', 'show')->name('show');
    Route::delete('/{choice_id}', 'destroy')->name('destroy');
});

// choice
Route::controller(ChoiceController::class)
// ->get('/', 'index')->name('index') // questions以下ではない為別途切り出し
->prefix('choices')
->as('api.choices.')
->middleware(['web', 'auth:sanctum', 'role:'.Role::User->value])
->group(function () {
    Route::get('/', 'index')->name('index');
});

//　ダッシュボード
// dashboard/questions
Route::controller(DashboardQuestionController::class)
    ->prefix('dashboard/questions')
    ->as('api.dashboard.questions.')
    ->middleware(['web', 'auth:sanctum', 'role:'.Role::User->value])
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

// dashboard/choices
Route::controller(DashboardChoiceController::class)
    ->prefix('dashboard/choices')
    ->as('api.dashboard.choices.')
    ->middleware(['web', 'auth:sanctum', 'role:'.Role::User->value])
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

// ランキング
Route::controller(ApiRankingController::class)
    ->prefix('rankings')
    ->as('api.rankings.')
    ->middleware(['web', 'auth:sanctum', 'role:'.Role::User->value])
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });


// セッションチェック
Route::middleware('web')->get('/check-session', function () {
    return response()->json([
        'session' => session()->all(),
        'user' => Auth::user(),
    ]);
});