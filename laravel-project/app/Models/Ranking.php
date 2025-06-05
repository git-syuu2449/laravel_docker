<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ranking extends Model
{
    /** @use HasFactory<\Database\Factories\RankingFactory> */
    use HasFactory;
    use SoftDeletes;


    /**
     * 入力可能なカラム
     * 許可されないカラムは除外
     * @var array
     */
    protected $fillable = ['question_id', 'base_date', 'start_at', 'end_at', 'count', 'average'];

    /**
     * 質問
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Question>
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
