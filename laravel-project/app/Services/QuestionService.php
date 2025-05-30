<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Question;
use App\Models\QuestionImage;

class QuestionService
{

    /**
     * 質問テーブル、質問画像テーブルへの新規登録処理を行う
     * 画像用ディレクトリへの再配置も実施する。
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function createWithImages(Request $request, User $user): void
    {
        // トランザクション開始
        DB::beginTransaction();
        $savedFiles = [];

        try {
            // 質問テーブルへの登録を行う
            $question = Question::create([
                'user_id' => $user->id,
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'pub_date' => now(),
            ]);

            // 質問画像がある場合は登録を行う
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    // 画像の配置
                    $path = $image->store("images/question/{$question->id}", 'public');// @todo 画像のパスはなにか考える。
                    $filename = basename($path);
                    $savedFiles[] = $path;
                    Log::info('path: '.$path); // ~/laravel_docker/laravel-project/storage/public/images/question/15

                    // 質問画像テーブルへの登録を行う
                    QuestionImage::create([
                        'user_id' => $user->id,
                        'question_id' => $question->id,
                        'image' => "images/question/{$question->id}/{$filename}", // @todo 画像のパスはなにか考える。
                    ]);
                }
            }

            // 登録処理
            DB::commit();

        } catch (\Throwable $e) {
            // ロールバック処理
            DB::rollBack();
            // 配置した画像を削除する
            foreach ($savedFiles as $file) {
                Storage::delete($file);
            }
            throw $e;
        }
    }

}