<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class ApiQuestionController extends Controller
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
