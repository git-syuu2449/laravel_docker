<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionImage extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionImageFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * 入力可能なカラム
     * @var array
     */
    protected $fillable = ['question_id', 'user_id', 'image'];

    /**
     * 質問テーブルリレーション
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Question, QuestionImage>
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
