<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    /**
     * 質問削除
     */
    public function destroy($question_id)
    {
        try
        {
            $choice = Question::where([
                'id' => $question_id,
                'user_id' => Auth::id(),
            ])->firstOrFail();

            $choice->delete();

            return response()->json(['message' => '削除に成功しました'], 200);

        }
        // 404
        catch (ModelNotFoundException $e) {
            report($e);

            return response()->json(['message' => '削除に失敗しました'], 404);
        }
        catch (\Throwable $e) {
            report($e);

            return response()->json(['message' => '削除に失敗しました'], 500);
        }
    }

}
