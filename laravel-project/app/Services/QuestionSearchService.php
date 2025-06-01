<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Gate;

use App\Models\Question;

class QuestionSearchService
{

    /**
     * スコープを使用しない検索
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection<int, Question>
     */
    public function searchToParam(array $params)
    {
        $query = Question::query();

        if (!empty($params['title'])) {
            $query->where('title', 'like', '%' . $params['title'] . '%');
        }

        if (!empty($params['body'])) {
            $query->where('body', 'like', '%' . $params['body'] . '%');
        }

        if (!empty($params['pub_date_from'])) {
            $from = Carbon::parse($params['pub_date_from'])->startOfDay();
            $query->whereDate('pub_date', '>=', $from);
        }

        if (!empty($params['pub_date_to'])) {
            $to = Carbon::parse($params['pub_date_to'])->endOfDay();
            $query->whereDate('pub_date', '<=', $to);
        }

        $questions = $query->get();

        // 評価可能かを判定してセット
        $questions->each(function ($question) {
            $question['can_be_evaluated'] = Gate::allows('evaluate', $question);
        });

        return $questions;
    }

    /**
     * スコープを使用する検索
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection<int, Question>
     */
    public function search(array $params)
    {
        // 評価済み判定の為に子テーブルも読み込む
        $query = Question::with('choices');

        if (!empty($params['title'])) {
            $query->searchTitle($params['title']);
        }

        if (!empty($params['body'])) {
            $query->searchBody($params['body']);
        }

        if (!empty($params['pub_date_from'])) {
            $from = Carbon::parse($params['pub_date_from'])->startOfDay();
            $query->fromDateTime($from);
        }

        if (!empty($params['pub_date_to'])) {
            $to = Carbon::parse($params['pub_date_to'])->endOfDay();
            $query->toDateTime($to);
        }

        $questions = $query->get();

        // 評価可能かを判定してセット
        $questions->each(function ($question) {
            $question['can_be_evaluated'] = Gate::allows('evaluate', $question);
        });

        return $questions;
    }

}