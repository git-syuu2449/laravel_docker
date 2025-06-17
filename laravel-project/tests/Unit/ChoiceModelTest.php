<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Question;
use App\Models\Choice;
use App\Models\User;

class ChoiceModelTest extends TestCase
{
    // 各テスト後にデータベースをリセット
    use RefreshDatabase;
    use WithFaker;

    /**
     * questionテーブルとのリレーション
     * @return void
     */
    #[Test]
    public function it_belongs_to_a_question()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user1->id]);
        $choice = Choice::factory()->create(['question_id' => $question->id, 'user_id' => $user2->id]);

        // インスタンス確認
        $this->assertInstanceOf(Question::class, $choice->question);
        // PK一致確認
        $this->assertEquals($question->id, $choice->question->id);
    }
}
