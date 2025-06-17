<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

use App\Models\Ranking;
use App\Http\Requests\SearchRankingRequest;
use App\Enums\RankingType;

class RankingSearchService
{
    /**
     * スコープを使用する検索
     * Sqlが重くなる場合は、表示用のランキングテーブルを作成して対処する
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection<int, Ranking>
     */
    public function search(array $params)
    {
        // valueからenumへ変換
        $type = RankingType::from($params['type']);
        // 最新の日付を取得
        $query = Ranking::query()->type($type)->maxBaseDate();
        $ranking = $query->first();

        // ランキングが存在しない場合
        if (!$ranking)
        {
            return collect();
        }

        $base_date = Carbon::parse($ranking->base_date);
        
        // ランキングに紐づく質問を取得Limitとソートも行う
        return Ranking::query()
            ->withQuestion()
            ->type($type)
            ->baseDate($base_date)
            ->topOrder()
            ->topLimit()
            ->get();
    }

}