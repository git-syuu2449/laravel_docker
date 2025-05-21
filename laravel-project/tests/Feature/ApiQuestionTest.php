<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Question;
use App\Enums\Role;

class ApiQuestionTest extends TestCase
{

    // 各テスト後にデータベースをリセット
    use RefreshDatabase;
    use WithFaker;
    
     /**
      * 一覧取得可能かつデータが存在するケース
      * @return void
      */
    #[Test]
    public function exist_api_question_index()
    {
        // 必要データの作成
        $user = User::factory()->create();
        $question = Question::factory()->create();

        $target_url = route('api.questions.index');

        $response = $this
            // ログイン状態にする
            ->actingAs($user)
            ->getJson($target_url);

        // ステータスコード確認
        $response->assertStatus(201);
    }

    /**
      * 一覧取得可能かつデータが存在しないケース
      * @return void
      */
      #[Test]
      public function not_exist_api_question_index()
      {
          // 必要データの作成
          $user = User::factory()->create();
          $question = Question::factory()->create();
  
          $target_url = route('api.questions.index');
  
          $response = $this
              // ログイン状態にする
              ->actingAs($user)
              ->getJson($target_url);
  
          // ステータスコード確認
          $response->assertStatus(201);
      }

    /**
      * 一覧取得不可能なケース
      * @return void
      */
      #[Test]
      public function impossible_api_question_index()
      {
          // 必要データの作成
          $user = User::factory()->create([
            'role' => Role::Guest // 権限のないユーザー
          ]);
          $question = Question::factory()->create();
  
          $target_url = route('api.questions.index');
  
          $response = $this
              // ログイン状態にする
              ->actingAs($user)
              ->getJson($target_url);
  
          // ステータスコード確認
          $response->assertStatus(403);
      }
}
