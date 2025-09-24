<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Question;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_question()
    {
        $question = Question::factory()->create();

        $this->assertDatabaseHas('question', [
            'id' => $question->id,
            'title' => $question->title,
        ]);
    }

    /** @test */
    public function it_can_have_many_answers()
    {
        $question = Question::factory()->create();
        $answers = Answer::factory()->count(3)->for($question)->create();

        $this->assertCount(3, $question->answers);
        foreach ($answers as $answer) {
            $this->assertTrue($question->answers->contains($answer));
        }
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $question = Question::factory()->create();
        $question->delete();

        $this->assertSoftDeleted('question', [
            'id' => $question->id,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $question = Question::factory()->for($user)->create();

        $this->assertEquals($user->id, $question->user_id);
    }
}
