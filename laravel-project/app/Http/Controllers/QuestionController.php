<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuestionRequest;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::all();
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
        Log::debug($request); // debug
        // バリデーション処理
        $validated = $request->validated();

        // バリデーション通過後の処理
        Log::debug($validated); // debug
        // dd($request->all()); // debug
        // 登録処理
        // Question::create($validated);
        // 個別に登録処理
        Question::create([
            'question_text' => $validated['question_text'],
            'pub_date' => now()
        ]);

        // return response()->json(['message' => '登録完了'], 201);

        // 一覧画面に遷移
        return redirect()->route('questions.index', ['p' => 1])
        ->with('status', '質問登録に成功しました');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $question = Question::findOrFail($id)->choices;
        // $question = Question::getLeftQuestionWithChoices($id);
        $question = Question::WithChoices()->id($id)->first();
        // dump($question->toArray()); //debug
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
