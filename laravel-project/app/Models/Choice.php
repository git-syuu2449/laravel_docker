<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    protected $fillable = ['question_id', 'choice_text', 'votes'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
