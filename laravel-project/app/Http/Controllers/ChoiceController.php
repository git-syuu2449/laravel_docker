<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreChoiceRequest;

use App\Models\Choice;

class ChoiceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * /questions/{question_id}/choices
     */
    public function store(StoreChoiceRequest $request, $question_id)
    {
        Log::debug('questions/id/choices');
        Log::debug($request->toArray()); // debug
        // バリデーション処理
        $validated = $request->validated();

        // 登録に使用するログインユーザー
        $user = Auth::user();

        // バリデーション通過後の処理
        // 登録処理
        Choice::create([
            'question_id' => $question_id,
            'user_id' => $user->id,
            'choice_text' => $validated['choice_text'],
            'votes' => $validated['votes'],
        ]);
        // reload処理用にsessionに保存
        session()->flash('message', '登録完了');
        return response()->json(['message' => '登録完了'], 201);
    }
}
