<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Enums\Role;
use App\Http\Controllers\Api\QuestionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get(uri: '/questions', action: [QuestionController::class, 'index'])->name('api.questions.index')
// ->middleware([ 'web', \App\Http\Middleware\DebugSanctumStateful::class]);
->middleware(['web', 'auth:sanctum' ,'role:'.Role::User->value]);

// Route::apiResource('questions', ApiQuestionController::class)
// ->only(['index'])
// ->middleware(['auth:sanctum', 'role:'.Role::User->value]);


Route::middleware('web')->get('/check-session', function () {
    return response()->json([
        'session' => session()->all(),
        'user' => Auth::user(),
    ]);
});