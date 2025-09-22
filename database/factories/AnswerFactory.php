<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition()
    {
        return [
            'body' => $this->faker->sentence(),
            'question_id' => Question::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
