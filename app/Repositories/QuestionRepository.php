<?php

namespace App\Repositories;

use App\Models\Question;

class QuestionRepository
{
    public function all()
    {
        return Question::all();
    }

    public function find($id)
    {
        return Question::findOrFail($id);
    }

    public function create(array $data)
    {
        return Question::create($data);
    }

    public function update(Question $question, array $data)
    {
        $question->update($data);
        return $question;
    }

    public function delete(Question $question)
    {
        return $question->delete();
    }
}
