<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Question;
use Illuminate\Http\Request;

use App\Http\Requests\SearchQuestionRequest;
use App\Services\QuestionSearchService;

class QuestionController extends Controller
{
    /**
     * 一覧表示
     * 検索機能つき
     */
    public function index(SearchQuestionRequest $request, QuestionSearchService $service)
    {
        // バリデーション処理
        $validated = $request->validated();

        // 渡された検索条件を元に検索
        $questions = $service->search($validated);

        return response()->json([
            'status' => true,
            'questions' => $questions
        ], 201);
    }

}
