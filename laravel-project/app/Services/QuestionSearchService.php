<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Question;
use App\Models\QuestionImage;

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
            $query->whereDate('pub_date', '>=', $params['pub_date_from']);
        }

        if (!empty($params['pub_date_to'])) {
            $query->whereDate('pub_date', '<=', $params['pub_date_to']);
        }

        return $query->get();
    }

    /**
     * スコープを使用する検索
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Collection<int, Question>
     */
    public function search(array $params)
    {
        $query = Question::query();

        if (!empty($params['title'])) {
            $query->searchTitle($params['title']);
        }

        if (!empty($params['body'])) {
            $query->searchBody($params['body']);
        }

        if (!empty($params['pub_date_from'])) {
            $query->fromDate($params['pub_date_from']);
        }

        if (!empty($params['pub_date_to'])) {
            $query->toDate($params['pub_date_to']);
        }

        return $query->get();
    }

}