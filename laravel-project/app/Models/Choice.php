<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Summary of Choice
 */
class Choice extends Model
{
    /** @use HasFactory<\Database\Factories\Models\ChoiceFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * 入力可能なカラム
     * @var array
     */
    protected $fillable = ['question_id', 'user_id', 'choice_text', 'votes'];

    /**
     * questionリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Question, Choice>
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * userリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Choice>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * id
     * @param mixed $query
     * @param mixed $id
     */
    public function scopeId(Builder $query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * user_id
     * @param mixed $query
     * @param mixed $id
     */
    public function scopeUserId(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * ユーザーに紐づく質問に対する回答の取得(Join使用)
     * @param mixed $user_id
     */
    public static function getChoiceWithQuestion($user_id)
    {
        return DB::table('choices')
            ->join('questions', 'choices.question_id', '=', 'questions.id')
            ->where('questions.user_id', $user_id)
            ->select('choices.*', 'questions.title as question_title')
            ->get();
    }

    /**
     * 削除用Url
     * @return string
     */
    public function getDeleteUrlAttribute()
    {
        // route('api.choices.destroy', ['question_id'=>$choice->question_id, 'choice_id' => $choice->id])
        return route('api.questions.choices.destroy', [
            'question_id' => $this->question_id,
            'choice_id' => $this->id,
        ]);
    }

}
