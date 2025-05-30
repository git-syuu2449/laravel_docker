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
     * user_id
     * @param mixed $query
     * @param mixed $id
     */
    public function scopeUserId(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * title部分一致
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $text
     * @return Builder
     */
    public function scopeSearchTitle(Builder $query, string $text)
    {
        return $query->where('title', 'like', "%{$text}%");
    }

    /**
     * body部分一致
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $text
     * @return Builder
     */
    public function scopeSearchBody(Builder $query, string $text)
    {
        return $query->where('body', 'like', "%{$text}%");
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

    /**
     * withChoices
     * select * from `choices` where `choices`.`question_id` in (1, ...) and `choices`.`deleted_at` is null
     * @param mixed $query
     */
    public function scopeWithChoices($query)
    {
        return $query->with('choices');
    }

    /**
     * withQuestionImage
     * @param mixed $query
     */
    public function scopeWithQuestionImages($query)
    {
        return $query->with('questionImages');
    }
}