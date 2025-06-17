<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Question;
use App\Models\Choice;
use App\Models\User;


class QuestionModelTest extends TestCase
{

    // 各テスト後にデータベースをリセット
    use RefreshDatabase;
    use WithFaker;

    /**
     * choiceテーブルとのリレーション
     * @return void
     */
    #[Test]
    public function it_has_many_choices()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user1->id]);
        $choices = Choice::factory()->count(3)->create(['user_id' => $user2->id, 'question_id' => $question->id]);

        $this->assertCount(3, $question->choices);
        $this->assertInstanceOf(Choice::class, $question->choices->first());
    }

    /**
     * 
     */
    #[Test]
    public function scope_with_choices_eager_loads_choices()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $question = Question::factory(['user_id' => $user1->id])->has(Choice::factory(['user_id' => $user2->id])->count(2))->create();

        $questionWithChoices = Question::withChoices()->find($question->id);

        $this->assertTrue($questionWithChoices->relationLoaded('choices'));
        $this->assertCount(2, $questionWithChoices->choices);
    }

}
