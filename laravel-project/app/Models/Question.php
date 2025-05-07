<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Summary of Question
 */
class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    /**
     * 入力可能なカラム
     * @var array
     */
    protected $fillable = ['question_text', 'pub_date'];

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }
}
