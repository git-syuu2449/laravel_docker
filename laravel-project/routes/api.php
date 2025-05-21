<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Enums\Role;
use App\Http\Controllers\ApiQuestionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get(uri: '/questions', action: [ApiQuestionController::class, 'index'])->name('api.questions.index')
->middleware(['auth:sanctum', 'role:'.Role::User->value]);

// Route::apiResource('questions', ApiQuestionController::class)
// ->only(['index'])
// ->middleware(['auth:sanctum', 'role:'.Role::User->value]);
