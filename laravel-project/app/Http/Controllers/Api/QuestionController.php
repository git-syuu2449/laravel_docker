<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * 一覧表示
     * 検索機能つき
     */
    public function index()
    {
        $questions = Question::all();
        return response()->json([
            'status' => true,
            'questions' => $questions
        ], 201);
    }

}
