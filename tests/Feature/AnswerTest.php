<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_answer()
    {
        $answer = Answer::factory()->create();

        $this->assertDatabaseHas('answer', [
            'id' => $answer->id,
            'body' => $answer->body,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_question()
    {
        $question = Question::factory()->create();
        $answer = Answer::factory()->for($question)->create();

        $this->assertInstanceOf(Question::class, $answer->question);
        $this->assertEquals($question->id, $answer->question->id);
    }

    /** @test */
    public function it_can_be_soft_deleted()
    {
        $answer = Answer::factory()->create();
        $answer->delete();

        $this->assertSoftDeleted('answer', [
            'id' => $answer->id,
        ]);
    }
}
