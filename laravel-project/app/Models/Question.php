<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * @var array
     */
    protected $fillable = ['user_id', 'question_text', 'pub_date'];

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

    /**
     * Summary of scopeWithChoices
     * select * from `choices` where `choices`.`question_id` in (1, ...) and `choices`.`deleted_at` is null
     * @param mixed $query
     */
    public function scopeWithChoices($query)
    {
        return $query->with('choices');
    }

    /**
     * Summary of scopeWithQuestionImages
     * @param mixed $query
     */
    public function scopeWithQuestionImages($query)
    {
        return $query->with('questionImages');
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
}
