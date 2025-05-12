<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionStoreTest extends TestCase
{

    // 各テスト後にデータベースをリセット
    use RefreshDatabase;
    use WithFaker;
    
     /**
      * 正常に質問登録可能なケース
      * @return void
      */
    #[Test]
    public function it_registers_a_question_successfully()
    {
        $target_url = route("questions.store");
        $test_data = [
            // "question_text" => "testDataOK" // factory
            "question_text" => $this->faker->realText(200)
        ];
        $response = $this->post($target_url, $test_data);

        // リダイレクト確認
        $response->assertRedirect(route('questions.index'));
        // ステータスコード確認 @todo コードの一元管理
        $response->assertStatus(302);
        // DB確認
        $this->assertDatabaseHas('questions', $test_data);
    }

    /**
     * 必須チェック
     * @return void
     */
    #[Test]
    public function question_text_is_required()
    {
        $target_url = route("questions.store");
        $test_data = [
            "question_text" => "" 
        ];
        $response = $this->post($target_url, $test_data);

        // エラーが発生すること
        $response->assertSessionHasErrors('question_text');
    }
}
