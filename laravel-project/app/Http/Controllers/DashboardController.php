<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Question;
use App\Models\Choice;

class DashboardController extends Controller
{
    public function index()
    {
        // ログインユーザーの投稿データに紐づく選択の取得（一対多対多）
        // 1.ユーザーに紐づく質問の取得を行う(一対多)
        // 2.ユーザーに紐づく質問に紐づく回答の取得を行う(多対多)
        // 3.ユーザーに紐づく回答の取得を行う(一対多)
        $data = null;

        $user = Auth::user();
        // 1と2
        $questions = Question::with(['choices'])->userId($user->id)->get();

        // 3
        $choices = Choice::userId($user->id)->get();

        return view(view: 'dashboard', data: compact('questions', 'choices'));
    }
}
