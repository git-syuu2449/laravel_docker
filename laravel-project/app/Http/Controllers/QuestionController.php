<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Http\Requests\StoreQuestionRequest;
use App\Services\QuestionService;
use App\Services\QuestionSearchService;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(QuestionSearchService $service)
    {
        $questions = $service->search([]);

        return view(view: 'questions.index', data: compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        self::outputLog();
        return view(view: 'questions.create');
    }

    public function createA()
    {
        self::outputLog();
        return view(view: 'questions.createA');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request)
    {
        // バリデーション処理
        $validated = $request->validated();

        // バリデーション通過後の処理

        // 登録に使用するログインユーザー
        $user = Auth::user();

        try {

            // 質問テーブルと質問画像テーブルへの登録
            // serive層に委譲
            $service = new QuestionService();
            $service->createWithImages($request, $user); // バリデーション後に整形等している場合は$request->validated()を渡す

            // 一覧画面に遷移
            return redirect()->route('questions.index')
                ->with('status', '質問登録に成功しました');

        } catch (\Throwable $e) {
            report($e);

            return response()->json(['message' => '登録に失敗しました'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $question = Question::query()->with(['choices', 'questionImages'])->findOrFail($id);
        // 評価可能か
        $question->can_be_evaluated = Gate::allows('evaluate', $question);
        return view(view: 'questions.show',data: compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }

    private function outputLog()
    {
        Log::debug('セッションの old 入力値:', session()->getOldInput()); // debug
        Log::debug('セッションのバリデーションエラー:', session('errors') ? session('errors')->all() : []); // debug
    }
}
