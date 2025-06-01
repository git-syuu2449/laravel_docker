<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    /**
     * 評価可能か
     * リレーション事前読み込み必須
     * @param \App\Models\User $user
     * @param \App\Models\Question $question
     * @return bool
     */
    public function evaluate(User $user, Question $question): bool
    {
        // 自分の投稿は評価不可
        if ($user->id === $question->user_id) {
            return false;
        }

        // 評価済みであれば評価不可
        return !$question->choices->contains(fn($choice) =>
            $choice->user_id === $user->id
        );
    }
}
