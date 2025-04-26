<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\Choice;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // 以下削除処理 一元管理する際は別途切り出し
        // 外部キー制約を無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // テーブルをクリア
        Question::truncate();
        Choice::truncate();

        // 外部キー制約を有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Question::factory()
            ->count(10)
            ->create()
            ->each(function (Question $question) {
                // 偶数件目は未回答
                if ($question->id % 2 == 0) return;
                // 回答
                Choice::factory()
                    ->count(3)
                    ->create([
                        'question_id' => $question->id,
                        'votes' => rand(0,5)
                    ]);
            });
    }
}
