<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

use App\Enums\RankingType;
use App\Models\Question;
use App\Models\Ranking;
use Carbon\Carbon as CarbonCarbon;

class VoteRanking extends Command
{
    /**
     * コマンドの実行方法
     * type: 1:日 2:週 3:月 4:年
     * date: 起点日
     * @var string
     */
    protected $signature = 'app:vote-ranking {type} {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '評価のランキング集計';

    /**
     * 集計元となるデータをtype、起点の日付から取得しサマリーする
     */
    public function handle()
    {
        try
        {
            $this->info('ランキング集計バッチ開始');
            // Log::debug("引数",['param' => $this->argument('type'),'opt'=> $this->argument('date')]);

            // typeの一致がない場合はエラー
            $type = RankingType::from($this->argument('type')); //?? RankingType::DAILY;
            // 日付の形式が異常な場合はエラー
            $base_date = Carbon::parse($this->argument('date'));

            // typeで日付を生成
            [$start_at, $end_at] = $type->getDateRange($base_date);

            $query = Question::withWhereHas('choices', function ($qu) use($start_at, $end_at){
                // todo 内部でもスコープ使えるので対応。
                $qu->where('created_at', '>=', $start_at)
                    ->where('created_at', '<=', $end_at);
            });

            $questions = $query->get();
            $questions->each(function($question) use($type, $base_date, $start_at, $end_at)
            {
                // 質問に紐づく評価に対してサマリ
                $total = $question->choices->sum('votes');
                $average = $question->choices->avg('votes');
                $count = $question->choices->count();

                //　集計対象がいない場合はreturn
                // if ($count < 0) return true; // 質問に対して作成はする。レスポンスの問題で軽量化するなら判定を有効にする。

                // 登録・更新処理
                Ranking::upsert([
                    [
                        'question_id' => $question->id,
                        'type' => $type,
                        'start_at' => $start_at,
                        'end_at' => $end_at,
                        'base_date' => $base_date,
                        'count' => $count ?? 0,
                        'total' => $total ?? 0,
                        'average' => $average ?? 0,
                        'created_at' => now(), // 作成日時等はupsertを使う場合は明示的に指定が必要
                        'updated_at' => now(),
                    ]
                ], ['question_id', 'type', 'base_date'], ['count', 'total', 'average', 'end_at', 'base_date', 'updated_at']);
                
            });

            $this->info('ランキング集計バッチ終了');
        }
        catch (\Throwable $e)
        {
            report($e);
            Log::channel('critical_errors')->error($e);
            $this->error('ランキング集計バッチ異常終了');
        }

    }
}
