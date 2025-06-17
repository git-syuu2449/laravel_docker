<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Enums\RankingType;

class RankingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = RankingType::getLabelsValues();

        // 検索に使用する初期検索タイプ
        $default_type = RankingType::DAILY->value;
        
        // データ取得はvueでAPI
        return view(view: 'rankings.index', data: compact('types', 'default_type'));
    }
}
