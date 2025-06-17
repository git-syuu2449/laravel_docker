<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ranking;
use Illuminate\Http\Request;

use App\Services\RankingSearchService;
use App\Http\Requests\SearchRankingRequest;

class RankingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRankingRequest $request, RankingSearchService $service)
    {
        $validated = $request->validated();
        // ランキング形式切替時：デフォルト取得時それぞれ検索
        $rankings = $service->search($validated);

        return response()->json([
            'status' => true,
            'rankings' => $rankings
        ], 201);
    }
}
