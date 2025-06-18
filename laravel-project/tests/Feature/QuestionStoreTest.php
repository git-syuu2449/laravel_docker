<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use App\Models\User;
use App\Models\Question;

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
        $user = User::factory()->create();

        // 画像をテスト用に作成
        Storage::fake('public');
        // $file = UploadedFile::fake()->image('test.jpg', 600, 600);
        $file = UploadedFile::fake()->create('dummy.jpg', 100, 'image/jpeg');

        $test_data = [
            "title" => $this->faker->realText(200),
            "body" => $this->faker->realText(500),
            "images" => [$file],
        ];
        $response = $this->actingAs($user)->post($target_url, $test_data);

        // リダイレクト確認
        $response->assertRedirect(route('questions.index'));
        // ステータスコード確認
        $response->assertStatus(302);
        // DB確認
        unset($test_data["images"]);
        $this->assertDatabaseHas('questions', $test_data);
        $this->assertDatabaseHas('question_images', ["user_id" => $user->id]);
    }

    /**
     * 必須チェック
     * @return void
     */
    #[Test]
    public function title_is_required()
    {
        $target_url = route("questions.store");
        $user = User::factory()->create();
        $test_data = [
            "title" => "" ,
            "body" => "本文の登録テスト",
        ];
        $response = $this->actingAs($user)->post($target_url, $test_data);

        // エラーが発生すること
        $response->assertSessionHasErrors('title');
    }
}
