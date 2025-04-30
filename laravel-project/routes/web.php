<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuestionController;

// Question(個別)
// Route::get(uri: "/questions", action: "App\Http\Controllers\QuestionController@index")->name('questions.index');
Route::get(uri: '/questions', action: [QuestionController::class, 'index'])->name('questions.index');
// Question(一括)
// Route::get(uri: "question", action: QuestionController::class);
