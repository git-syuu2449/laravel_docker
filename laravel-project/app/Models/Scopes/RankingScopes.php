<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

use App\Enums\RankingType;

/**
 * Rankingモデルのスコープ
 */
trait RankingScopes
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
     * ランキングタイプ
     * @param mixed $query
     * @param \App\Enums\RankingType $type
     */
    public function scopeType($query, RankingType $type)
    {
        return $query->where('type', $type);
    }

    /**
     * 基準日
     * @param mixed $query
     * @param mixed $base_date
     * @return void
     */
    public function scopeBaseDate($query, Carbon $base_date)
    {
        return $query->where('base_date', $base_date);
    }

    /**
     * 最新レコードを取得する
     * @param mixed $query
     */
    public function scopeMaxBaseDate($query)
    {
        return $query->latest('base_date');
    }

    /**
     * ソート順:ランキング
     * @param mixed $query
     */
    public function scopeTopOrder($query)
    {
        return $query->orderByDesc('total');
    }

    /**
     * 一覧表示する件数
     * @param mixed $query
     * @return void
     */
    public function scopeTopLimit($query)
    {
        return $query->limit(30);
    }

    /**
     * withQuestion
     * @param mixed $query
     */
    public function scopeWithQuestion($query)
    {
        return $query->with('question');
    }

    /**
     * withQuestion
     * @param mixed $query
     */
    public function scopeWithQuestionWithQuestionImages($query)
    {
        return $query->with('question.questionImages');
    }
}