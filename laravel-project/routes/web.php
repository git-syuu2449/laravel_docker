<?php

use App\Enums\Role;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

# 独自追加
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboradController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

// ログイン関連
require __DIR__.'/auth.php';

// 開発関連
require __DIR__.'/dev.php';

Route::get('/', function () {
    return view('welcome');
});

// 正確には user/{$id}/dashboardが妥当
Route::get(uri: 'dashboard',action:  [ DashboardController::class, 'index'])
->middleware(['auth', 'verified', 'role:'.Role::User->value])
->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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
->middleware(['auth', 'verified', 'role:'.Role::User->value])
->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::get('/createA', 'createA')->name('createA');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
});

Route::post(uri: '/questions/{id}/choices', action: [ChoiceController::class, 'store'])->name('choices.store')
->middleware(['auth', 'verified', 'role:'.Role::User->value]);



// 管理画面
Route::get(uri: 'admin/',action:  [ AdminDashboradController::class, 'index'])
->middleware(['auth', 'verified', 'role:' . Role::Admin->value])
->name('admin.dashboard');

Route::controller(AdminQuestionController::class)
->prefix('admin.questions')
->as('admin.questions.')
->middleware(['auth', 'verified', 'role:'.Role::Admin->value])
->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
});