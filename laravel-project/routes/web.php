<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\QuestionController;

// Question(個別)
// Route::get(uri: "/questions", action: "App\Http\Controllers\QuestionController@index")->name('questions.index');
// Route::get(uri: '/questions', action: [QuestionController::class, 'index'])->name('questions.index');
// Route::get(uri: '/questions/create', action: [QuestionController::class, 'create'])->name('questions.create');

// Question(グループ:controllerベース)
// prefixを書くと省略した書き方が可能.
// 固定パスは動的パラメータより上に書かないと動的な方に捕まる(createとshowの関係)
/*
  GET|HEAD   questions .......................................... questions.index › QuestionController@index
  POST       questions .......................................... questions.store › QuestionController@store
  GET|HEAD   questions/create ................................. questions.create › QuestionController@create
  GET|HEAD   questions/{id} ....................................... questions.show › QuestionController@show
*/
Route::controller(QuestionController::class)
->prefix('questions')
->as('questions.')
->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::get('/createA', 'createA')->name('createA');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
});