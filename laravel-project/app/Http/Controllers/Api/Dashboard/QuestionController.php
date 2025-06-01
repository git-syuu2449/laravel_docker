<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class QuestionController extends Controller
{
    /**
     * 一覧表示
     * 質問一覧に紐づく評価も合わせて取得
     */
    public function index()
    {
        $questions = Question::with(['choices'])->userId(Auth::id())->get();

        $questions->each(function (Question $question) {
            $question['delete_url'] = $question->delete_url;
        });

        return response()->json([
            'status' => true,
            'questions' => $questions
        ], 201);
    }

}
