<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Question;
use App\Models\Choice;

class ChoiceStoreTest extends TestCase
{
    // 各テスト後にデータベースをリセット
    use RefreshDatabase;
    use WithFaker;
    
     /**
      * 正常に登録可能なケース
      * @return void
      */
    #[Test]
    public function it_registers_a_choice_successfully()
    {
        // 一対多構成の為、一のデータを作成
        $question = Question::factory()->create();

        $target_url = route('choices.store', $question->id);
        $test_data = [
            "question_id" => $question->id,
            "choice_text" => $this->faker->realText(200),
            "votes" => 2,
        ];
        $response = $this->post($target_url, $test_data);

        // リダイレクト確認は非同期の為不要
        // $response->assertRedirect(route('questions.index'));
        // ステータスコード確認 @todo コードの一元管理
        $response->assertStatus(201);
        // DB確認
        $this->assertDatabaseHas('choices', $test_data);
    }

    /**
     * 必須チェック
     * @return void
     */
    #[Test]
    public function choice_text_is_required()
    {
        // 一対多構成の為、一のデータを作成
        $question = Question::factory()->create();

        $target_url = route('choices.store', $question->id);
        $test_data = [
            "question_id" => $question->id,
            "choice_text" => null,
            "votes" => 2,
        ];
        $response = $this->post($target_url, $test_data);

        // エラーが発生すること
        $response->assertSessionHasErrors('choice_text');
    }
}
