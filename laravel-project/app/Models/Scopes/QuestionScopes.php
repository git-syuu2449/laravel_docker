<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Questionモデルのスコープ
 */
trait QuestionScopes
{

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
     * question_text部分一致
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $text
     * @return Builder
     */
    public function scopeSearchText(Builder $query, string $text)
    {
        return $query->where('question_text', 'like', "%{$text}%");
    }

    /**
     * 投稿日時From
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $date
     * @return Builder
     */
    public function scopeFromDate(Builder $query, string $date)
    {
        return $query->where('pub_date', '>=', $date);
    }

    /**
     * 投稿日時To
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $date
     * @return Builder
     */
    public function scopeToDate(Builder $query, string $date)
    {
        return $query->where('pub_date', '<=', $date);
    }
}