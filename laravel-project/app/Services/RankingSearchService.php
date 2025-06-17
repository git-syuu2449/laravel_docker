<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

use App\Models\Ranking;
use App\Http\Requests\SearchRankingRequest;
use App\Enums\RankingType;
use App\Constants\ImagePath;

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
        $rankings = $this->getRankingWithQuestion($params);
        $cast = $rankings->map(function ($ranking) {
            $image_url = $ranking->question?->questionImages?->first()?->image ?? null;
            $image_url = $image_url ? Storage::url($image_url) : asset(ImagePath::NO_IMAGE);
            return [
            'id' => $ranking->id,
            'total' => $ranking->total,
            'average' => $ranking->average,
            'count' => $ranking->count,
            'question_id' => $ranking->question->id,
            'question_title' => $ranking->question->title,
            'question_iamge' => $image_url,
            'base_date' => $ranking->base_date,
            ];
        });

        Log::debug($cast);

        return $cast;
    }

    /**
     * ランキング、質問、画像を取得する
     * @param \App\Enums\RankingType $type
     * @return \Illuminate\Database\Eloquent\Collection<int, Ranking>
     */
    private function getRankingWithQuestion(array $params): Collection
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
            ->withQuestionWithQuestionImages()
            ->type($type)
            ->baseDate($base_date)
            ->topOrder()
            ->topLimit()
            ->get();
    }

}