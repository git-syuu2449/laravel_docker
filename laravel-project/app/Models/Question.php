<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Models\Scopes\QuestionScopes;

/**
 * Summary of Question
 */
class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;
    use SoftDeletes;

    // スコープ
    use QuestionScopes;

    /**
     * 入力可能なカラム
     * 許可されないカラムは除外
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'body', 'pub_date'];

    /**
     * bootオーバーライド
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        // 削除実行時の動作に子を削除する機能を追加
        static::deleting(function ($question) {
            // 子要素を一括削除
            // リレーションで削除
            // 評価
            $question->choices()->delete();
            // 質問画像
            $question->questionImages()->delete();
        });
    }


    /**
     * 評価可能かのプロパティ
     * @var bool
     */
    public bool $can_be_evaluated = false;

    /**
     * Summary of choices
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Choice, Question>
     */
    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function questionImages()
    {
        return $this->hasMany(QuestionImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Summary of getInnerQuestionWithChoices
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Collection<int, Question>
     */
    public static function getInnerQuestionWithChoices($id)
    {
        return $questions = Question::join('choices', 'choices.question_id', '=', 'questions.id')
        ->select('questions.*', 'choices.choice_text as choice_text')
        ->where('questions.id', $id)
        ->get();
    }

    /**
     * Summary of getLeftQuestionWithChoices
     * @param mixed $id
     */
    public static function getLeftQuestionWithChoices($id)
    {
        return $questions = Question::leftJoin('choices', 'choices.question_id', '=', 'questions.id')
        ->select('questions.*', 'choices.choice_text as choice_text')
        ->where('questions.id', $id)
        ->get();
    }

    /**
     * 投稿したユーザーの判定
     * @return bool
     */
    public function getIsPostQuestionAttribute()
    {
        return Auth::id() !== $this->user_id;
    }

    /**
     * 削除用Url
     * @return string
     */
    public function getDeleteUrlAttribute()
    {
        return route('api.questions.destroy', [
            'id' => $this->id,
        ]);
    }

    /**
     * 質問詳細Url
     * @return string
     */
    public function getQuestionShowUrlAttribute()
    {
        return route('questions.show', [
            'id' => $this->id,
        ]);
    }
}
