<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Question;

class ApiQuestionTest extends TestCase
{

    // 各テスト後にデータベースをリセット
    use RefreshDatabase;
    use WithFaker;
    
     /**
      * 一覧取得可能なケース
      * @return void
      */
    #[Test]
    public function can_api_question_index()
    {
        $question = Question::factory()->create();
        $target_url = route('api/questions.index');

        $response = $this->get($target_url);

        // リダイレクト確認
        $response->assertRedirect(route('questions.index'));
        // ステータスコード確認
        $response->assertStatus(201);
    }
}
